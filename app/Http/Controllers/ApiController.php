<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProjectUser;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignProject;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\Project;
use App\Models\Utility;
use App\Models\Tag;
use App\Models\ProjectTask;
use App\Models\StockReport;
use App\Models\TimeTracker;
use App\Models\TrackPhoto;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    //
    use ApiResponser;

    public function login(Request $request)
    {

        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        $settings              = Utility::settings(auth()->user()->id);

        $settings = [
            'shot_time' => isset($settings['interval_time']) ? $settings['interval_time'] : 0.5,
        ];

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user' => auth()->user()->id,
            //            'user'=> auth()->user()->id,
            'settings' => $settings,
        ], 'Login successfully.');
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([], 'Tokens Revoked');
    }


    public function getProjects(Request $request)
    {

        $user = auth()->user();

        if ($user->type != 'company') {
            $assign_pro_ids = ProjectUser::where('user_id', $user->id)->pluck('project_id');

            //            $project_s      = Project::with('tasks')->select(
            //                [
            //                    'project_name',
            //                    'id',
            //                    'client_id',
            //                ]
            //            )->whereIn('id', $assign_pro_ids)->get()->toArray();

            $project_s      = Project::with('tasks')->whereIn('id', $assign_pro_ids)->get()->toArray();
        } else {

            //            $project_s = Project::with('tasks')->select(
            //                [
            //                    'project_name',
            //                    'id',
            //                    'client_id',
            //                ]
            //            )->where('created_by', $user->id)->get()->toArray();

            $project_s = Project::with('tasks')->where('created_by', $user->id)->get()->toArray();
        }

        return $this->success([
            'projects' => $project_s,
        ], 'Get Project List successfully.');
    }


    public function addTracker(Request $request)
    {

        $user = auth()->user();
        if ($request->has('action') && $request->action == 'start') {

            $validatorArray = [
                'task_id' => 'required|integer',
            ];
            $validator      = \Validator::make(
                $request->all(),
                $validatorArray
            );
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 401);
            }
            $task = ProjectTask::find($request->task_id);

            if (empty($task)) {
                return $this->error('Invalid task', 401);
            }

            $project_id = isset($task->project_id) ? $task->project_id : '';
            TimeTracker::where('created_by', '=', $user->id)->where('is_active', '=', 1)->update(['end_time' => date("Y-m-d H:i:s")]);

            $track['name']        = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['project_id']  = $project_id;
            $track['is_billable'] =  $request->has('is_billable') ? $request->is_billable : 0;
            $track['tag_id']      = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['start_time']  = $request->has('time') ?  date("Y-m-d H:i:s", strtotime($request->input('time'))) : date("Y-m-d H:i:s");
            $track['task_id']     = $request->has('task_id') ? $request->input('task_id') : '';
            $track['created_by']  = $user->id;
            $track                = TimeTracker::create($track);
            $track->action        = 'start';

            return $this->success($track, 'Track successfully create.');
        } else {
            $validatorArray = [
                'task_id' => 'required|integer',
                'traker_id' => 'required|integer',
            ];
            $validator      = Validator::make(
                $request->all(),
                $validatorArray
            );
            if ($validator->fails()) {
                return Utility::error_res($validator->errors()->first());
            }
            $tracker = TimeTracker::where('id', $request->traker_id)->first();
            // dd($tracker);
            if ($tracker) {
                $tracker->end_time   = $request->has('time') ?  date("Y-m-d H:i:s", strtotime($request->input('time'))) : date("Y-m-d H:i:s");
                $tracker->is_active  = 0;
                $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
                $tracker->save();
                return $this->success($tracker, 'Stop time successfully.');
            }
        }
    }
    public function uploadImage(Request $request)
    {
        $user = auth()->user();
        $image_base64 = base64_decode($request->img);
        $file = $request->imgName;
        if ($request->has('tracker_id') && !empty($request->tracker_id)) {
            $app_path = storage_path('uploads/traker_images/') . $request->tracker_id . '/';
            if (!file_exists($app_path)) {
                mkdir($app_path, 0777, true);
            }
        } else {
            $app_path = storage_path('uploads/traker_images/');
            if (is_dir($app_path)) {
                mkdir($app_path, 0777, true);
            }
        }
        $file_name =  $app_path . $file;
        file_put_contents($file_name, $image_base64);
        $new = new TrackPhoto();
        $new->track_id = $request->tracker_id;
        $new->user_id  = $user->id;
        $new->img_path  = 'uploads/traker_images/' . $request->tracker_id . '/' . $file;
        $new->time  = $request->time;
        $new->status  = 1;
        $new->save();
        return $this->success([], 'Uploaded successfully.');
    }

    public function customercreation()
    {
        return Customer::all();
    }
    public function createcustomer(Request $request)
    {
        return $request;
    }
    public function storecustomer(Request $request)
    {
        if ($request->api_key!='#chillLife') {
            return response()->json(['error' => 'Customer not found.'], 404);
        }
        $ctmr = Customer::create([
            'customer_id' => $request->customer_id,
            'vivape_id' => $request->vivape_id,
            'vivape_user_id' => $request->vivape_user_id,
            'identity' => $request->identity,
            'identity_attachment' => $request->identity_attachment,
            'name' => $request->name,
            'balance' => $request->balance,
            'email' => $request->email,
            'password' => $request->password,
            'contact' => $request->contact,
            'avatar' => $request->avatar,
            'is_active' => $request->is_active,
            'created_by' => $request->created_by,
            'email_verified_at' => date('Y-m-d'),
            'billing_name' => $request->name,
            'billing_country' => $request->billing_country,
            'billing_state' => $request->billing_state,
            'billing_city' => $request->billing_city,
            'billing_phone' => $request->billing_phone,
            'billing_zip' => $request->billing_zip,
            'billing_address' => $request->billing_address,
            'shipping_name' => $request->shipping_name,
            'shipping_country' => $request->shipping_country,
            'shipping_state' => $request->shipping_state,
            'shipping_city' => $request->shipping_city,
            'shipping_phone' => $request->shipping_phone,
            'shipping_zip' => $request->shipping_zip,
            'shipping_address' => $request->shipping_address,

        ]);

        $data = [
            'message' => 'Successfully created customer',
            'api_note' => 'success',
            'customer' => $ctmr
        ];

        return response()->json($data);
    }


    public function invoicecreation()
    {
        return Invoice::all();
    }
    public function createinvoice(Request $request)
    {
        return $request;
    }
    public function storeInvoice(Request $request)
    {

        if ($request->api_key!='#chillLife') {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        $sku = $request->sku;
        $product = ProductService::where('sku', $sku)->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found for SKU: ' . $sku], 404);
        }

        $bank_acc = BankAccount::where('id', $request->account_id)->first();
        if (!$bank_acc) {
            return response()->json(['error' => 'No Bank Account for #: ' . $request->account_id], 404);
        }
        $customer = Customer::where('customer_id', $request->customer_id)->first();
        if (!$customer) {
            return response()->json(['error' => 'No Customer for #: ' . $request->customer_id], 404);
        }

        $ps_category = ProductServiceCategory::where('name', '=', $request->category_name)->get()->first();

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_id' => $request->invoice_id,
            'customer_id' => $customer->id,
            'sku' => $request->sku,
            'commission' => $request->commission,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'send_date' => $request->send_date,
            'category_id' => $ps_category->id,
            'ref_number' => $request->ref_number,
            'type' => $request->type,
            'status' => $request->status,
            'shipping_display' => $request->shipping_display??0,
            'discount_apply' => $request->discount_apply??0,
            'created_by' => $request->created_by??2,
        ]);

        // Create the invoice product
        $invoiceProduct = InvoiceProduct::create([
            'product_id' => $product->id,
            'invoice_id' => $invoice->id,
            'quantity' => 1,
            'tax' => 0,
            'discount' => 0,
            'price' => $request->amount,
        ]);
        $stockReport = StockReport::create([
            'product_id' => $product->id,
            'quantity' => 1,
            'type' => $request->type,
            'type_id' => $invoice->id,
            'description' => $request->description,
        ]);

        // Create payment and transaction if status is 4
        if ($request->status == 4) {
            $invoicePayment = InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'date' => $invoice->issue_date,
                'amount' => $request->amount,
                'account_id' => $request->account_id,
                'payment_method' => $request->payment_method,
                'order_id' => $request->invoice_id,
                'currency' => 'EUR',
                'txn_id' => $request->ref_number,
                'payment_type' => $request->payment_type,
                'receipt' => $request->receipt??'',
                'reference' => $request->ref_number,
                'description' => $request->description,
            ]);

            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'user_type' => $request->user_type??'Customer',
                'account' => $request->account_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $invoice->issue_date,
                'created_by' => $request->created_by??2,
                'payment_id' => $invoicePayment->id,
                'category' => $ps_category->id??1,
            ]);

            $new_bal = $customer->addBalance($request->amount);

            $bankAccount = BankAccount::where('id', $request->account_id)->get()->first();
            if (!$bankAccount) {
                $bankAccount = BankAccount::create([
                    'holder_name' => $request->holder_name,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'chart_account_id' => $product->sale_chartaccount_id,
                    'opening_balance' => $request->amount,
                    'contact_number' => $request->contact_number,
                    'bank_address' => $request->bank_address,
                    'created_by' => $request->created_by,
                ]);
            }
        }

        // // Fetch invoice with associated products
        // $invoiceWithProducts = Invoice::with('products')->find($invoice->id);

        $data = [
            'message' => 'Successfully created invoice',
            'api_note' => 'success',
            'Invoice' => $invoice,
            'InvoiceProduct' => $invoiceProduct,
            'StockReport' => $stockReport,
            'InvoicePayment' => $invoicePayment,
            'Transaction' => $transaction,
            'BankAccount' => $bankAccount,
            'New Balance' => $new_bal,
        ];

        return response()->json($data);
    }
}
