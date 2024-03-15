<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProjectUser;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignProject;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Project;
use App\Models\Utility;
use App\Models\Tag;
use App\Models\ProjectTask;
use App\Models\TimeTracker;
use App\Models\TrackPhoto;

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
            'shot_time'=> isset($settings['interval_time'])?$settings['interval_time']:0.5,
        ];

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user'=> auth()->user()->id,
//            'user'=> auth()->user()->id,
            'settings' =>$settings,
        ],'Login successfully.');
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([],'Tokens Revoked');
    }


    public function getProjects(Request $request)
    {

        $user = auth()->user();

        if($user->type!='company')
        {
            $assign_pro_ids = ProjectUser::where('user_id',$user->id)->pluck('project_id');

//            $project_s      = Project::with('tasks')->select(
//                [
//                    'project_name',
//                    'id',
//                    'client_id',
//                ]
//            )->whereIn('id', $assign_pro_ids)->get()->toArray();

            $project_s      = Project::with('tasks')->whereIn('id', $assign_pro_ids)->get()->toArray();

        }
        else
        {

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
        ],'Get Project List successfully.');
    }


    public function addTracker(Request $request){

        $user = auth()->user();
        if($request->has('action') && $request->action == 'start'){

            $validatorArray = [
                'task_id' => 'required|integer',
            ];
            $validator      = \Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return $this->error($validator->errors()->first(), 401);
            }
            $task= ProjectTask::find($request->task_id);

            if(empty($task)){
                return $this->error('Invalid task', 401);
            }

            $project_id = isset($task->project_id)?$task->project_id:'';
            TimeTracker::where('created_by', '=', $user->id)->where('is_active', '=', 1)->update(['end_time' => date("Y-m-d H:i:s")]);

            $track['name']        = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['project_id']  = $project_id;
            $track['is_billable'] =  $request->has('is_billable')? $request->is_billable:0;
            $track['tag_id']      = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['start_time']  = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
            $track['task_id']     = $request->has('task_id') ? $request->input('task_id') : '';
            $track['created_by']  = $user->id;
            $track                = TimeTracker::create($track);
            $track->action        ='start';

            return $this->success( $track,'Track successfully create.');
        }else{
            $validatorArray = [
                'task_id' => 'required|integer',
                'traker_id' =>'required|integer',
            ];
            $validator      = Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return Utility::error_res($validator->errors()->first());
            }
            $tracker = TimeTracker::where('id',$request->traker_id)->first();
            // dd($tracker);
            if($tracker)
            {
                $tracker->end_time   = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
                $tracker->is_active  = 0;
                $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
                $tracker->save();
                return $this->success( $tracker,'Stop time successfully.');
            }
        }

    }
    public function uploadImage(Request $request){
        $user = auth()->user();
        $image_base64 = base64_decode($request->img);
        $file =$request->imgName;
        if($request->has('tracker_id') && !empty($request->tracker_id)){
            $app_path = storage_path('uploads/traker_images/').$request->tracker_id.'/';
            if (!file_exists($app_path)) {
                mkdir($app_path, 0777, true);
            }

        }else{
            $app_path = storage_path('uploads/traker_images/');
            if (is_dir($app_path)) {
                mkdir($app_path, 0777, true);
            }
        }
        $file_name =  $app_path.$file;
        file_put_contents( $file_name, $image_base64);
        $new = new TrackPhoto();
        $new->track_id = $request->tracker_id;
        $new->user_id  = $user->id;
        $new->img_path  = 'uploads/traker_images/'.$request->tracker_id.'/'.$file;
        $new->time  = $request->time;
        $new->status  = 1;
        $new->save();
        return $this->success( [],'Uploaded successfully.');
    }

    public function customercreation()
    {
        return Customer::all();
    }
    public function createcustomer(Request $request)
    {
        return $request;
    }
    public function storecustomer(Request $request, Customer $customers){
            // $request->validate([
            //     'customer_id' => 'required|max:30|string',
            //     'name' => 'required|max:30|string',
            //     'email' => 'required|email',
            //     'password' => 'required|max:30|string',
            //     'contact' => 'required|max:30|string',
            //     'avatar' => 'required|max:30|string',
            //     'is_active' => 'required|max:30|string',
            //     'created_by' => 'required|max:30|string',
            //     'email_verified_at' => 'required|max:30|string',
            //     'billing_name' => 'required|max:30|string',
            //     'billing_country' => 'required|max:30|string',
            //     'billing_state' => 'required|max:30|string',
            //     'billing_city' => 'required|max:30|string',
            //     'billing_phone' => 'required|max:30|string',
            //     'billing_zip' => 'required|max:30|string',
            //     'billing_address' => 'required|max:30|string',
            //     'shipping_name' => 'required|max:30|string',
            //     'shipping_country' => 'required|max:30|string',
            //     'shipping_state' => 'required|max:30|string',
            //     'shipping_city' => 'required|max:30|string',
            //     'shipping_phone' => 'required|max:30|string',
            //     'shipping_zip' => 'required|max:30|string',
            //     'shipping_address' => 'required|max:30|string',
            // ]);

            $ctmr = Customer::create([
                'customer_id'=> $request->customer_id,
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> $request->password,
                'contact'=> $request->contact,
                'avatar'=> $request->avatar,
                'is_active'=> $request->is_active,
                'created_by'=> $request->created_by,
                'email_verified_at'=> $request->email_verified_at,
                'billing_name'=> $request->billing_name,
                'billing_country'=> $request->billing_country,
                'billing_state'=> $request->billing_state,
                'billing_city'=> $request->billing_city,
                'billing_phone'=> $request->billing_phone,
                'billing_zip'=> $request->billing_zip,
                'billing_address'=> $request->billing_address,
                'shipping_name'=> $request->shipping_name,
                'shipping_country'=> $request->shipping_country,
                'shipping_state'=> $request->shipping_state,
                'shipping_city'=> $request->shipping_city,
                'shipping_phone'=> $request->shipping_phone,
                'shipping_zip'=> $request->shipping_zip,
                'shipping_address'=> $request->shipping_address,
                'vivape_id'=> $request->vivape_id,
                'vivape_user_id'=> $request->vivape_user_id,
                'identity'=> $request->identity,
                'identity_attachment'=> $request->identity_attachment,
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
    public function storeinvoice(Request $request, Invoice $invoice, InvoiceProduct $product){
        // return $request;
        $invoice = Invoice::create([
            'invoice_id'=> $request->invoice_id,
            'customer_id'=> $request->customer_id,
            'sku'=> $request->sku,
            'discount'=> $request->discount,
            'commision'=> $request->commision,
            'issue_date'=> $request->issue_date,
            'due_date'=> $request->due_date,
            'send_date'=> $request->send_date,
            'category_id'=> $request->category_id,
            'ref_number'=> $request->ref_number,
            'status'=> $request->status,
            'shipping_display'=> $request->shipping_display,
            'discount_apply'=> $request->discount_apply,
            'created_by'=> $request->created_by,
        ]);

        // invoice cration
        // check invoice type
        $type = $request->type;
        // search and get product from system based on product type
        return $get_product = Product::where('name','like','%'.$type.'%')->get()->first();
        // create the invoice_product
        $create_invoice_product = InvoiceProduct::create( [
            'product_id' => $get_product->id,
            'invoice_id' => $invoice->id,
            'quantity' => 1,
            'tax' => 0,
            'discount' => 0,
            'total' => $request->amount,
        ]);
    
        
    
        $invoiceWithProducts = Invoice::with('products')->find($invoice->id);
    
        $data = [
            'message' => 'Successfully created invoice',
            'api_note' => 'success',
            'invoice' => $invoiceWithProducts,
        ];
    
        return response()->json($data);
    }
    

}
