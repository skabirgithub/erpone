<?php

namespace App\Models;

use App\Mail\CommonEmailTemplate;
use App\Mail\TestMail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;

class Utility extends Model
{
    // private static $settings = NULL;
    private static $getsettings = NULL;
    private static $getsettingsid = NULL;
    private static $taxsData = NULL;
    private static $taxRateData = NULL;
    private static $taxData = NULL;
    private static $taxes = NULL;

    // public static function settings(){
    //     if(self::$settings == null){
    //         self::$settings = self::fetchSetting();
    //     }
    //     return self::$settings;
    // }

    private static $languageSetting = NULL;

    public static function getSetting()
    {
        if (self::$getsettings == null) {
            $data = DB::table('settings');
            $data = $data->where('created_by', '=', 1)->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
            self::$getsettings = $data;
        }
        return self::$getsettings;
    }

    public static function getSettingById($id)
    {
        if (self::$getsettingsid == null) {
            $data = DB::table('settings');
            $data = $data->where('created_by', '=', $id)->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
            self::$getsettingsid = $data;
        }
        return self::$getsettingsid;
    }

    public static function settings()
    {
        if (\Auth::check()) {
            $data = Utility::getSettingById(\Auth::user()->creatorId());
            //        $data=$data->where('created_by','=',\Auth::user()->creatorId())->get();
            if (count($data) == 0) {
                $data = Utility::getSetting();
            }
        } else {
            $data = Utility::getSetting();
        }

        $settings = [
            "site_currency" => "BDT",
            "site_currency_symbol" => "TK",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "timezone" => '',
            "company_name" => "Skoder ERP",
            "company_address" => "14/X, ICT Tower, Agargaon, Dhaka-1207, Bangladesh",
            "company_city" => "Dhaka",
            "company_state" => "Dhaka",
            "company_zipcode" => "1207",
            "company_country" => "Bangladesh",
            "company_telephone" => "+8801750726094",
            "invoice_prefix" => "#INVO",
            "journal_prefix" => "#JUR",
            "invoice_color" => "ffffff",
            "proposal_prefix" => "#PROP",
            "proposal_color" => "ffffff",
            "bill_prefix" => "#BILL",
            "expense_prefix" => "#EXP",
            "bill_color" => "ffffff",
            "customer_prefix" => "#CUST",
            "vender_prefix" => "#VEND",
            "footer_title" => "Tech Partner Skoder ERP",
            "footer_notes" => "Tech partner for your Business",
            "invoice_template" => "template1",
            "bill_template" => "template1",
            "proposal_template" => "template1",
            "registration_number" => "1234",
            "vat_number" => "1234",
            "default_language" => "en",
            "enable_stripe" => "1",
            "enable_paypal" => "",
            "paypal_mode" => "",
            "paypal_client_id" => "",
            "paypal_secret_key" => "",
            "stripe_key" => "1234",
            "stripe_secret" => "1234",
            "decimal_number" => "2",
            "tax_type" => "",
            "shipping_display" => "on",
            "display_landing_page" => "on",
            "employee_prefix" => "#EMP00",
            'leave_status' => '1',
            "bug_prefix" => "#BGT",
            'title_text' => 'Skoder ERP',
            'footer_text' => 'Tech Partner Skoder ERP',
            "company_start_time" => "09:00",
            "company_end_time" => "18:00",
            'gdpr_cookie' => 'off',
            "interval_time" => "",
            "zoom_apikey" => "",
            "zoom_apisecret" => "",
            "slack_webhook" => "",
            "telegram_accestoken" => "",
            "telegram_chatid" => "",
            "enable_signup" => "on",
            "email_verification" => "on",
            'cookie_text' => 'We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.
',
            "company_logo_light" => "logo-light.png",
            "company_logo_dark" =>  "logo-dark.png",
            "company_favicon" => "favicon.png",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "color" => "",
            "SITE_RTL" => "off",
            "purchase_prefix" => "#PUR",
            "purchase_color" => "ffffff",
            "purchase_template" => "template1",
            "pos_color" => "ffffff",
            "pos_template" => "template1",
            "pos_prefix" => "#POS",

            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",

            "purchase_logo" => "logo-light.png",
            "proposal_logo" => "logo-light.png",
            "invoice_logo" => "logo-light.png",
            "bill_logo" => "logo-light.png",
            "pos_logo" => "logo-light.png",
            "contract_prefix" => "#CON",

            "barcode_type" => "code128",
            "barcode_format" => "css",


            'new_user' => '1',
            'new_client' => '1',
            'new_support_ticket' => '1',
            'lead_assigned' => '1',
            'deal_assigned' => '1',
            'new_award' => '1',
            'customer_invoice_sent' => '1',
            'new_invoice_payment' => '1',
            'new_payment_reminder' => '1',
            'new_bill_payment' => '1',
            'bill_resent' => '1',
            'proposal_sent' => '1',
            'complaint_resent' => '1',
            'leave_action_sent' => '1',
            'payslip_sent' => '1',
            'promotion_sent' => '1',
            'resignation_sent' => '1',
            'termination_sent' => '1',
            'transfer_sent' => '1',
            'trip_sent' => '1',
            'vender_bill_sent' => '1',
            'warning_sent' => '1',
            'new_contract' => '1',
            'vat_gst_number_switch' => 'off',
            'google_calendar_enable' => 'off',
            'google_calender_json_file' => '',

            'meta_title' => 'Skoder ERP',
            'meta_desc' => 'Tech partner for your business',
            'meta_image' => 'logo-light.png',

            'enable_cookie' => 'on',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => 'We use cookies!',
            'cookie_description' => 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title' => 'Strictly necessary cookies',
            'strictly_cookie_description' => 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description' => 'For any queries in relation to our policy on cookies and your choices, please contact us',
            'contactus_url' => '#',

            'twilio_sid' => '',
            'twilio_token' => '',
            'twilio_from' => '',
            'twilio_from' => '',
            'chat_gpt_key' => '',
            "ip_restrict" => "off",

            'mail_driver' => '',
            'mail_host' => '',
            'mail_port' => '',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => '',
            'mail_from_address' => '',
            'mail_from_name' => '',

            'recaptcha_module' => '',
            'google_recaptcha_key' => '',
            'google_recaptcha_secret' => '',

            'pusher_app_id' => '',
            'pusher_app_key' => '',
            'pusher_app_secret' => '',
            'pusher_app_cluster' => '',

        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        config(
            [
                'captcha.secret' => $settings['google_recaptcha_secret'],
                'captcha.sitekey' => $settings['google_recaptcha_key'],
                'options' => [
                    'timeout' => 30,
                ],
            ]
        );

        return $settings;
    }

    public static function settingsById($user_id)
    {
        $data = Utility::getSettingById($user_id);

        $settings = [
            "site_currency" => "BDT",
            "site_currency_symbol" => "TK",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "invoice_prefix" => "#INVO",
            "invoice_color" => "ffffff",
            "proposal_prefix" => "#PROP",
            "proposal_color" => "ffffff",
            "bill_prefix" => "#BILL",
            "expense_prefix" => "#EXP",
            "bill_color" => "ffffff",
            "customer_prefix" => "#CUST",
            "vender_prefix" => "#VEND",
            "footer_title" => "",
            "footer_notes" => "",
            "invoice_template" => "template1",
            "bill_template" => "template1",
            "proposal_template" => "template1",
            "registration_number" => "",
            "vat_number" => "",
            "default_language" => "en",
            "enable_stripe" => "",
            "enable_paypal" => "",
            "paypal_mode" => "",
            "paypal_client_id" => "",
            "paypal_secret_key" => "",
            "stripe_key" => "",
            "stripe_secret" => "",
            "decimal_number" => "2",
            "tax_type" => "",
            "shipping_display" => "on",
            "journal_prefix" => "#JUR",
            "display_landing_page" => "on",
            "employee_prefix" => "#EMP00",
            'leave_status' => '1',
            "bug_prefix" => "#ISSUE",
            'title_text' => '',
            'footer_text' => '',
            "company_start_time" => "09:00",
            "company_end_time" => "18:00",
            'gdpr_cookie' => 'off',
            "interval_time" => "",
            "zoom_apikey" => "",
            "zoom_apisecret" => "",
            "slack_webhook" => "",
            "telegram_accestoken" => "",
            "telegram_chatid" => "",
            "enable_signup" => "on",
            "email_verification" => "on",
            'cookie_text' => 'We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.
',
            "company_logo_light" => "logo-light.png",
            "company_logo_dark" =>  "logo-dark.png",
            "company_favicon" => "favicon.png",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "color" => "",
            "SITE_RTL" => "off",
            "purchase_prefix" => "#PUR",
            "purchase_color" => "ffffff",
            "purchase_template" => "template1",
            "proposal_logo" => "",
            "purchase_logo" => "",
            "invoice_logo" => "",
            "bill_logo" => "",
            "pos_logo" => "",
            "pos_color" => "ffffff",
            "pos_template" => "template1",


            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",

            "barcode_type" => "code128",
            "barcode_format" => "css",

            'new_user' => '1',
            'new_client' => '1',
            'new_support_ticket' => '1',
            'lead_assigned' => '1',
            'deal_assigned' => '1',
            'new_award' => '1',
            'customer_invoice_sent' => '1',
            'new_invoice_payment' => '1',
            'new_payment_reminder' => '1',
            'new_bill_payment' => '1',
            'bill_resent' => '1',
            'proposal_sent' => '1',
            'complaint_resent' => '1',
            'leave_action_sent' => '1',
            'payslip_sent' => '1',
            'promotion_sent' => '1',
            'resignation_sent' => '1',
            'termination_sent' => '1',
            'transfer_sent' => '1',
            'trip_sent' => '1',
            'vender_bill_sent' => '1',
            'warning_sent' => '1',
            'new_contract' => '1',

            'vat_gst_number_switch' => 'off',
            'google_calendar_enable' => 'on',
            'google_calender_json_file' => '',

            'meta_title' => '',
            'meta_desc' => '',
            'meta_image' => '',

            'enable_cookie' => 'on',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => 'We use cookies!',
            'cookie_description' => 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title' => 'Strictly necessary cookies',
            'strictly_cookie_description' => 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description' => 'For any queries in relation to our policy on cookies and your choices, please contact us',
            'contactus_url' => '#',

            'twilio_sid' => '',
            'twilio_token' => '',
            'twilio_from' => '',
            'chat_gpt_key' => '',
            "ip_restrict" => "off",
            "timezone" => '',

            'pusher_app_id' => '',
            'pusher_app_key' => '',
            'pusher_app_secret' => '',
            'pusher_app_cluster' => '',

        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static $emailStatus = [
        'new_user' => 'New User',
        'new_client' => 'New Client',
        'new_support_ticket' => 'New Support Ticket',
        'lead_assigned' => 'Lead Assigned',
        'deal_assigned' => 'Deal Assigned',
        'new_award' => 'New Award',
        'customer_invoice_sent' => 'Customer Invoice Sent',
        'new_invoice_payment' => 'New Invoice Payment',
        'new_payment_reminder' => 'New Payment Reminder',
        'new_bill_payment' => 'New Bill Payment',
        'bill_resent' => 'Bill Resent',
        'proposal_sent' => 'Proposal Sent',
        'complaint_resent' => 'Complaint Resent',
        'leave_action_sent' => 'Leave Action Sent',
        'payslip_sent' => 'Payslip Sent',
        'promotion_sent' => 'Promotion Sent',
        'resignation_sent' => 'Resignation Sent',
        'termination_sent' => 'Termination Sent',
        'transfer_sent' => 'Transfer Sent',
        'trip_sent' => 'Trip Sent',
        'vender_bill_sent' => 'Vendor Bill Sent',
        'warning_sent' => 'Warning Sent',
        'new_contract' => 'New Contract',
    ];

    public static function languages()
    {
        if (self::$languageSetting == null) {
            $languages = Utility::langList();

            if (\Schema::hasTable('languages')) {
                $settings = Utility::settings();
                if (!empty($settings['disable_lang'])) {
                    $disabledlang = explode(',', $settings['disable_lang']);
                    $languages = Language::whereNotIn('code', $disabledlang)->pluck('full_name', 'code');
                } else {
                    $languages = Language::pluck('full_name', 'code');
                }
                self::$languageSetting = $languages;
            }
        }

        return self::$languageSetting;
    }

    public static function getValByName($key)
    {

        $setting = Utility::settings();

        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }

        return $setting[$key];
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }

    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            '6676ef',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];

        return $arr;
    }

    public static function priceFormat($settings, $price)
    {

        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, $settings['decimal_number']) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public static function currencySymbol($settings)
    {
        return $settings['site_currency_symbol'];
    }

    public static function dateFormat($settings, $date)
    {
        return date($settings['site_date_format'], strtotime($date));
    }

    public static function timeFormat($settings, $time)
    {
        return date($settings['site_time_format'], strtotime($time));
    }
    public static function purchaseNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["purchase_prefix"] . sprintf("%05d", $number);
    }
    public static function posNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["pos_prefix"] . sprintf("%05d", $number);
    }

    public static function contractNumberFormat($number)
    {

        $settings = self::settings();
        return $settings["contract_prefix"] . sprintf("%05d", $number);
    }


    public static function invoiceNumberFormat($settings, $number)
    {

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public static function proposalNumberFormat($settings, $number)
    {
        return $settings["proposal_prefix"] . sprintf("%05d", $number);
    }

    public static function customerProposalNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["proposal_prefix"] . sprintf("%05d", $number);
    }

    public static function customerInvoiceNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }
    public static function customerPosNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["pos_prefix"] . sprintf("%05d", $number);
    }

    public static function billNumberFormat($settings, $number)
    {
        return $settings["bill_prefix"] . sprintf("%05d", $number);
    }

    public static function vendorBillNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["bill_prefix"] . sprintf("%05d", $number);
    }

    public static function getTax($tax)
    {
        if (self::$taxes == null) {
            $tax = Tax::find($tax);
            self::$taxes = $tax;
        }
        return self::$taxes;
    }

    public static function tax($taxes)
    {
        if (self::$taxsData == null) {
            $taxArr = explode(',', $taxes);
            $taxes  = [];
            foreach ($taxArr as $tax) {
                $taxes[] = self::getTax($tax);
            }
            self::$taxsData = $taxes;
        }

        return self::$taxsData;
    }
    public static function taxRate($taxRate, $price, $quantity, $discount = 0)
    {

        //        return ($taxRate / 100) * (($price-$discount) * $quantity);
        return (($price * $quantity) - $discount) * ($taxRate / 100);
    }

    public static function totalTaxRate($taxes)
    {

        if (self::$taxRateData == null) {
            $taxArr  = explode(',', $taxes);
            $taxRate = 0;
            foreach ($taxArr as $tax) {
                $tax     = self::getTax($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
            self::$taxRateData = $taxRate;
        }
        return self::$taxRateData;
    }

    //start for customer & vendor balance
    public static function userBalance($users, $id, $amount, $type)
    {
        if ($users == 'customer') {
            $user = Customer::find($id);
        } else {
            $user = Vender::find($id);
        }

        if (!empty($user)) {
            if ($type == 'credit') {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance + $amount;
                $user->balance = $userBalance;
                $user->save();
            } elseif ($type == 'debit') {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance - $amount;
                $user->balance = $userBalance;
                $user->save();
            }
        }
    }

    public static function updateUserBalance($users, $id, $amount, $type)
    {
        if ($users == 'customer') {
            $user = Customer::find($id);
        } else {
            $user = Vender::find($id);
        }

        if (!empty($user)) {
            if ($type == 'credit') {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance - $amount;
                $user->balance = $userBalance;
                $user->save();
            } elseif ($type == 'debit') {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance + $amount;
                $user->balance = $userBalance;
                $user->save();
            }
        }
    }

    //end for customer & vendor balance

    public static function bankAccountBalance($id, $amount, $type)
    {
        $bankAccount = BankAccount::find($id);
        if ($bankAccount) {
            if ($type == 'credit') {
                $oldBalance                   = $bankAccount->opening_balance;
                $bankAccount->opening_balance = $oldBalance + $amount;
                $bankAccount->save();
            } elseif ($type == 'debit') {
                $oldBalance                   = $bankAccount->opening_balance;
                $bankAccount->opening_balance = $oldBalance - $amount;
                $bankAccount->save();
            }
        }
    }

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        return $rgb; // returns an array with the rgb values
    }

    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for ($i = 0; $i < count($C); ++$i) {
            if ($C[$i] <= 0.03928) {
                $C[$i] = $C[$i] / 12.92;
            } else {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if ($L > 0.179) {
            $color = 'black';
        } else {
            $color = 'white';
        }

        return $color;
    }

    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static $chartOfAccountType = [
        'income' => 'Income',
        'expenses' => 'Expenses',
        'assets' => 'Assets',
        'liabilities' => 'Liabilities',
        'equity' => 'Equity',
        'costs of goods sold' => 'Costs of Goods Sold',
        'construction expenses' => 'Construction Expenses',
        'construction materials' => 'Construction Materials',
        'construction mecineries' => 'Construction Mecineries',
        'construction vehicles' => 'Construction Vehicles',
        'site expenses' => 'Site Expenses',

    ];


    public static $chartOfAccountSubType = array(
        "assets" => array(
            '1' => 'Current Asset',
            '2' => 'Inventory Asset',
            '3' => 'Non-current Asset',
            '4' => 'Machinery Asset',
            '5' => 'Transport Asset',
        ),
        "liabilities" => array(
            '1' => 'Current Liabilities',
            '2' => 'Long Term Liabilities',
            '3' => 'Share Capital',
            '4' => 'Retained Earnings',
        ),
        "equity" => array(
            '1' => 'Owners Equity',
            '2' => 'Investors Equity',
        ),
        "income" => array(
            '1' => 'Sales Revenue',
            '2' => 'Other Revenue',
            '3' => 'Project Revenue',
        ),
        "costs of goods sold" => array(
            '1' => 'Costs of Goods Sold',
        ),
        "expenses" => array(
            '1' => 'Payroll Expenses',
            '2' => 'General and Administrative expenses',
        ),
        "construction expenses" => array(
            '1' => 'Costs of Goods Purchase',
            '2' => 'Maintenance and Repair',
            '3' => 'Engineering and HRM',
            '4' => 'Head Office Expense',
            '5' => 'Travel and Allowances',
        ),
        "construction materials" => array(
            '1' => 'Structural Materials',
            '2' => 'Pavement Materials',
            '3' => 'Other Materials',
            '4' => 'Additional Considerations',
            '5' => 'Maintenance and Repair',
            '6' => 'Engineering',
        ),
        "construction mecineries" => array(
            '1' => 'Earthmoving and Excavation',
            '2' => 'Material Handling',
            '3' => 'Compaction',
            '4' => 'Concrete Work',
            '5' => 'Road Construction',
            '6' => 'Demolition',
            '7' => 'Other Equipment',
            '8' => 'Engineering and Maintenance',
            '9' => 'Transport and Garage',
        ),
        "construction vehicles" => array(
            '1' => 'Earthmoving and Transport',
            '2' => 'Excavation and Material Handling',
            '3' => 'Compaction and Paving',
            '4' => 'Concrete Work',
            '5' => 'Support Vehicles',
            '6' => 'Specialized Vehicles',
            '7' => 'Other Vehicles',
            '8' => 'Engineering and Maintenance',
            '9' => 'Transport and Garage',
        ),
        "site expenses" => array(
            '1' => 'Costs of Living',
            '2' => 'Maintenance and Repair',
            '3' => 'Engineering and HRM',
            '4' => 'Site Office Expense',
            '5' => 'TA-DA',
            '6' => 'Site Advance',
            '7' => 'Site Expense',
            '8' => 'Site Income',
            '9' => 'Foods and Snacks',
        ),
    );

    public static function chartOfAccountTypeData($company_id)
    {
        $chartOfAccountTypes = Self::$chartOfAccountType;
        foreach ($chartOfAccountTypes as $k => $type) {

            $accountType = ChartOfAccountType::create(
                [
                    'name' => $type,
                    'created_by' => $company_id,
                ]
            );

            $chartOfAccountSubTypes = Self::$chartOfAccountSubType;

            foreach ($chartOfAccountSubTypes[$k] as $subType) {
                ChartOfAccountSubType::create(
                    [
                        'name' => $subType,
                        'type' => $accountType->id,
                        'created_by' => $company_id ?? 2,
                    ]
                );
            }
        }
    }





    public static $chartOfAccount1 = array(

        // assets
        // current asset
        [
            'code' => '1101',
            'name' => 'Checking Account',
            'type' => 'Assets',
            'sub_type' => 'Current Asset',
        ],
        [
            'code' => '1102',
            'name' => 'Petty Cash',
            'type' => 'Assets',
            'sub_type' => 'Current Asset',
        ],
        [
            'code' => '1103',
            'name' => 'Account Receivables',
            'type' => 'Assets',
            'sub_type' => 'Current Asset',
        ],
        [
            'code' => '1104',
            'name' => 'Allowance for doubtful accounts',
            'type' => 'Assets',
            'sub_type' => 'Current Asset',
        ],
        // current asset
        // inventory asset
        [
            'code' => '1201',
            'name' => 'Inventory',
            'type' => 'Assets',
            'sub_type' => 'Inventory Asset',
        ],
        [
            'code' => '1202',
            'name' => 'Stock of Raw Materials',
            'type' => 'Assets',
            'sub_type' => 'Inventory Asset',
        ],
        [
            'code' => '1203',
            'name' => 'Stock of Work In Progress',
            'type' => 'Assets',
            'sub_type' => 'Inventory Asset',
        ],
        [
            'code' => '1204',
            'name' => 'Stock of Finished Goods',
            'type' => 'Assets',
            'sub_type' => 'Inventory Asset',
        ],
        [
            'code' => '1205',
            'name' => 'Goods Received Clearing account',
            'type' => 'Assets',
            'sub_type' => 'Inventory Asset',
        ],
        // inventory asset
        // non-current asset
        [
            'code' => '1301',
            'name' => 'Land and Buildings',
            'type' => 'Assets',
            'sub_type' => 'Non-current Asset',
        ],
        [
            'code' => '1302',
            'name' => 'Office Furniture and Equipement',
            'type' => 'Assets',
            'sub_type' => 'Non-current Asset',
        ],
        [
            'code' => '1303',
            'name' => 'Accum.depreciation-Furn. and Equip',
            'type' => 'Assets',
            'sub_type' => 'Non-current Asset',
        ],
        [
            'code' => '1304',
            'name' => 'Motor Vehicle',
            'type' => 'Assets',
            'sub_type' => 'Non-current Asset',
        ],
        [
            'code' => '1305',
            'name' => 'Accum.depreciation-Motor Vehicle',
            'type' => 'Assets',
            'sub_type' => 'Non-current Asset',
        ],
        // non-current asset
        // machinery asset
        [
            'code' => '1401',
            'name' => 'Earthmoving and Excavation Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        [
            'code' => '1402',
            'name' => 'Material Handling Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        [
            'code' => '1403',
            'name' => 'Compaction Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        [
            'code' => '1404',
            'name' => 'Concrete Work Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        [
            'code' => '1405',
            'name' => 'Road Construction Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        [
            'code' => '1406',
            'name' => 'Demolition Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        [
            'code' => '1407',
            'name' => 'Other Equipment Machinery',
            'type' => 'Assets',
            'sub_type' => 'Machinery Asset',
        ],
        // machinery asset
        // transport asset
        [
            'code' => '1501',
            'name' => 'Earthmoving and Transport',
            'type' => 'Assets',
            'sub_type' => 'Transport Asset',
        ],
        [
            'code' => '1502',
            'name' => 'Excavation and Material Handling Transport',
            'type' => 'Assets',
            'sub_type' => 'Transport Asset',
        ],
        [
            'code' => '1503',
            'name' => 'Compaction and Paving Transport',
            'type' => 'Assets',
            'sub_type' => 'Transport Asset',
        ],
        [
            'code' => '1504',
            'name' => 'Concrete Work Transport',
            'type' => 'Assets',
            'sub_type' => 'Transport Asset',
        ],
        [
            'code' => '1505',
            'name' => 'Support Vehicles',
            'type' => 'Assets',
            'sub_type' => 'Transport Asset',
        ],
        [
            'code' => '1506',
            'name' => 'Specialized Vehicles',
            'type' => 'Assets',
            'sub_type' => 'Transport Asset',
        ],
        // transport asset
        // assets

        // liabilities
        // Current Liabilities
        [
            'code' => '2101',
            'name' => 'Account Payable',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2102',
            'name' => 'Deferred Income',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2103',
            'name' => 'Accrued Income Tax-Central',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2104',
            'name' => 'Income Tax Payable',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2105',
            'name' => 'Accrued Franchise Tax',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2106',
            'name' => 'Vat Provision',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2107',
            'name' => 'Purchase Tax',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ], [
            'code' => '2108',
            'name' => 'VAT Pay / Refund',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2109',
            'name' => 'Zero Rated',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2110',
            'name' => 'Capital import',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2111',
            'name' => 'Standard Import',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2112',
            'name' => 'Capital Standard',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2113',
            'name' => 'Vat Exempt',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2114',
            'name' => 'Accrued Use Tax Payable',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2115',
            'name' => 'Accrued Wages',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2116',
            'name' => 'Accrued Comp Time',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2117',
            'name' => 'Accrued Holiday Pay',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2118',
            'name' => 'Accrued Vacation Pay',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2119',
            'name' => 'Accr. Benefits - Central Provident Fund',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ], [
            'code' => '2120',
            'name' => 'Accr. Benefits - Stock Purchase',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2121',
            'name' => 'Accr. Benefits - Med, Den',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2122',
            'name' => 'Accr. Benefits - Payroll Taxes',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2123',
            'name' => 'Accr. Benefits - Credit Union',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2124',
            'name' => 'Accr. Benefits - Savings Bond',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2125',
            'name' => 'Accr. Benefits - Group Insurance',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        [
            'code' => '2126',
            'name' => 'Accr. Benefits - Charity Cont.',
            'type' => 'Liabilities',
            'sub_type' => 'Current Liabilities',
        ],
        // Current Liabilities
        // Long Term Liabilities
        [
            'code' => '2201',
            'name' => 'Bank Loans',
            'type' => 'Liabilities',
            'sub_type' => 'Long Term Liabilities',
        ],
        [
            'code' => '2202',
            'name' => 'Loans from Shareholders',
            'type' => 'Liabilities',
            'sub_type' => 'Long Term Liabilities',
        ],
        // Long Term Liabilities
        // Share Capital
        [
            'code' => '2301',
            'name' => 'Common Shares',
            'type' => 'Liabilities',
            'sub_type' => 'Share Capital',
        ],
        // Share Capital
        // Retained Earnings
        [
            'code' => '2401',
            'name' => 'Reserves and Surplus',
            'type' => 'Liabilities',
            'sub_type' => 'Retained Earnings',
        ],
        [
            'code' => '2402',
            'name' => 'Owners Drawings',
            'type' => 'Liabilities',
            'sub_type' => 'Retained Earnings',
        ],
        // Retained Earnings
        // liabilities

        // equity
        [
            'code' => '3101',
            'name' => 'Opening Balances and adjustments',
            'type' => 'Equity',
            'sub_type' => 'Owners Equity',
        ],
        [
            'code' => '3102',
            'name' => 'Owners Contribution',
            'type' => 'Equity',
            'sub_type' => 'Owners Equity',
        ],
        [
            'code' => '3103',
            'name' => 'Profit and Loss ( current Year)',
            'type' => 'Equity',
            'sub_type' => 'Owners Equity',
        ],
        [
            'code' => '3104',
            'name' => 'Retained income',
            'type' => 'Equity',
            'sub_type' => 'Owners Equity',
        ],
        [
            'code' => '3201',
            'name' => 'Investment Receive',
            'type' => 'Equity',
            'sub_type' => 'Investors Equity',
        ],
        [
            'code' => '3202',
            'name' => 'Investment Withdraw',
            'type' => 'Equity',
            'sub_type' => 'Investors Equity',
        ],
        [
            'code' => '3203',
            'name' => 'Investment Savings',
            'type' => 'Equity',
            'sub_type' => 'Investors Equity',
        ],
        [
            'code' => '3204',
            'name' => 'Investment ROI',
            'type' => 'Equity',
            'sub_type' => 'Investors Equity',
        ],
        // equity
        // income
        [
            'code' => '4101',
            'name' => 'Sales Income',
            'type' => 'Income',
            'sub_type' => 'Sales Revenue',
        ],
        [
            'code' => '4102',
            'name' => 'Service Income',
            'type' => 'Income',
            'sub_type' => 'Sales Revenue',
        ],

        [
            'code' => '4201',
            'name' => 'Shipping and Handling',
            'type' => 'Income',
            'sub_type' => 'Other Revenue',
        ],
        [
            'code' => '4202',
            'name' => 'Sundry Income',
            'type' => 'Income',
            'sub_type' => 'Other Revenue',
        ],
        [
            'code' => '4203',
            'name' => 'Interest Received',
            'type' => 'Income',
            'sub_type' => 'Other Revenue',
        ],
        [
            'code' => '4204',
            'name' => 'Foreign Exchange Gain',
            'type' => 'Income',
            'sub_type' => 'Other Revenue',
        ],
        [
            'code' => '4205',
            'name' => 'Unallocated Income',
            'type' => 'Income',
            'sub_type' => 'Other Revenue',
        ],
        [
            'code' => '4206',
            'name' => 'Discounts Received',
            'type' => 'Income',
            'sub_type' => 'Other Revenue',
        ],

        [
            'code' => '4301',
            'name' => 'Project Govt Bill',
            'type' => 'Income',
            'sub_type' => 'Project Revenue',
        ],
        [
            'code' => '4302',
            'name' => 'Project Fund',
            'type' => 'Income',
            'sub_type' => 'Project Revenue',
        ],
        [
            'code' => '4303',
            'name' => 'Project Profit',
            'type' => 'Income',
            'sub_type' => 'Project Revenue',
        ],
        [
            'code' => '4304',
            'name' => 'Project Advance',
            'type' => 'Income',
            'sub_type' => 'Project Revenue',
        ],
        [
            'code' => '4305',
            'name' => 'Project Adjustment',
            'type' => 'Income',
            'sub_type' => 'Project Revenue',
        ],
        [
            'code' => '4306',
            'name' => 'Project Extension',
            'type' => 'Income',
            'sub_type' => 'Project Revenue',
        ],
        // income
        // cost of goods sold
        [
            'code' => '5101',
            'name' => 'Cost of Sales- On Services',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5102',
            'name' => 'Cost of Sales - Purchases',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5103',
            'name' => 'Operating Costs',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5104',
            'name' => 'Material Usage Varaiance',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5105',
            'name' => 'Breakage and Replacement Costs',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5106',
            'name' => 'Consumable Materials',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5107',
            'name' => 'Sub-contractor Costs',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5108',
            'name' => 'Purchase Price Variance',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5109',
            'name' => 'Direct Labour - COS',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5110',
            'name' => 'Purchases of Materials',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5111',
            'name' => 'Discounts Received',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        [
            'code' => '5112',
            'name' => 'Freight Costs',
            'type' => 'Costs of Goods Sold',
            'sub_type' => 'Costs of Goods Sold',
        ],
        // cost of goods sold
        // expense
        // Payroll Expenses
        [
            'code' => '6101',
            'name' => 'Salaries and Wages',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6102',
            'name' => 'Directors Fees & Remuneration',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6103',
            'name' => 'Wages - Overtime',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6104',
            'name' => 'Members Salaries',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6105',
            'name' => 'UIF Payments',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6106',
            'name' => 'Payroll Taxes',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6107',
            'name' => 'Workers Compensation ( Coida )',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6108',
            'name' => 'Normal Taxation Paid',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6109',
            'name' => 'General Benefits',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6110',
            'name' => 'Provisional Tax Paid',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6111',
            'name' => 'Inc Tax Exp - State',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6112',
            'name' => 'Taxes - Real Estate',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6113',
            'name' => 'Taxes - Personal Property',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6114',
            'name' => 'Taxes - Franchise',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        [
            'code' => '6115',
            'name' => 'Taxes - Foreign Withholding',
            'type' => 'Expenses',
            'sub_type' => 'Payroll Expenses',
        ],
        // Payroll Expenses
        // General and Administrative expenses
        [
            'code' => '6201',
            'name' => 'Accounting Fees',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6202',
            'name' => 'Advertising and Promotions',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6203',
            'name' => 'Bad Debts',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6204',
            'name' => 'Courier and Postage',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6205',
            'name' => 'Depreciation Expense',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6206',
            'name' => 'Insurance Expense',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6207',
            'name' => 'Bank Charges',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6208',
            'name' => 'Interest Paid',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6209',
            'name' => 'Office Expenses - Consumables',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6210',
            'name' => 'Printing and Stationary',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6211',
            'name' => 'Security Expenses',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6212',
            'name' => 'Subscription - Membership Fees',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6213',
            'name' => 'Electricity, Gas and Water',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6214',
            'name' => 'Rent Paid',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6215',
            'name' => 'Repairs and Maintenance',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6216',
            'name' => 'Motor Vehicle Expenses',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6217',
            'name' => 'Petrol and Oil',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6218',
            'name' => 'Equipment Hire - Rental',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6219',
            'name' => 'Telephone and Internet',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6220',
            'name' => 'Travel and Accommodation',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6221',
            'name' => 'Meals and Entertainment',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6222',
            'name' => 'Staff Training',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6223',
            'name' => 'Utilities',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6224',
            'name' => 'Computer Expenses',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6225',
            'name' => 'Registrations',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6226',
            'name' => 'Licenses',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6227',
            'name' => 'Foreign Exchange Loss',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        [
            'code' => '6228',
            'name' => 'Profit and Loss',
            'type' => 'Expenses',
            'sub_type' => 'General and Administrative expenses',
        ],
        // General and Administrative expenses
        // expense
        // construction expenses
        [
            'code' => '7101',
            'name' => 'Goods Purchase',
            'type' => 'Construction Expenses',
            'sub_type' => 'Costs of Goods Purchase',
        ],
        [
            'code' => '7102',
            'name' => 'Advance for Purchase',
            'type' => 'Construction Expenses',
            'sub_type' => 'Costs of Goods Purchase',
        ],
        [
            'code' => '7103',
            'name' => 'Dues for Purchase',
            'type' => 'Construction Expenses',
            'sub_type' => 'Costs of Goods Purchase',
        ],
        [
            'code' => '7104',
            'name' => 'Payment for Purchase',
            'type' => 'Construction Expenses',
            'sub_type' => 'Costs of Goods Purchase',
        ],

        [
            'code' => '7201',
            'name' => 'Construction Maintenance',
            'type' => 'Construction Expenses',
            'sub_type' => 'Maintenance and Repair',
        ],
        [
            'code' => '7202',
            'name' => 'Construction Repair',
            'type' => 'Construction Expenses',
            'sub_type' => 'Maintenance and Repair',
        ],

        [
            'code' => '7301',
            'name' => 'Construction Engineering',
            'type' => 'Construction Expenses',
            'sub_type' => 'Engineering and HRM',
        ],
        [
            'code' => '7302',
            'name' => 'Construction HRM',
            'type' => 'Construction Expenses',
            'sub_type' => 'Engineering and HRM',
        ],

        [
            'code' => '7401',
            'name' => 'Head Office Foods',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],
        [
            'code' => '7402',
            'name' => 'Head Office Snacks',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],
        [
            'code' => '7403',
            'name' => 'Head Office Stationary',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],
        [
            'code' => '7404',
            'name' => 'Head Office Internet',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],
        [
            'code' => '7405',
            'name' => 'Head Office Paper',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],
        [
            'code' => '7406',
            'name' => 'Head Office Service',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],
        [
            'code' => '7407',
            'name' => 'Head Office Maintenance',
            'type' => 'Construction Expenses',
            'sub_type' => 'Head Office Expense',
        ],

        [
            'code' => '7501',
            'name' => 'Travel Expense',
            'type' => 'Construction Expenses',
            'sub_type' => 'Travel and Allowances',
        ],
        [
            'code' => '7502',
            'name' => 'TA-DA',
            'type' => 'Construction Expenses',
            'sub_type' => 'Travel and Allowances',
        ],
        [
            'code' => '7503',
            'name' => 'Transportation',
            'type' => 'Construction Expenses',
            'sub_type' => 'Travel and Allowances',
        ],
        [
            'code' => '7504',
            'name' => 'Advance',
            'type' => 'Construction Expenses',
            'sub_type' => 'Travel and Allowances',
        ],
        // construction expenses

        // construction materials
        [
            'code' => '8101',
            'name' => 'Soil',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8102',
            'name' => 'Steel',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8103',
            'name' => 'Wood',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8104',
            'name' => 'Bricks',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8105',
            'name' => 'Blocks',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8106',
            'name' => 'Crushed stone',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8107',
            'name' => 'Gravel',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],
        [
            'code' => '8108',
            'name' => 'Sand',
            'type' => 'Construction Materials',
            'sub_type' => 'Structural Materials',
        ],


        [
            'code' => '8201',
            'name' => 'Asphalt',
            'type' => 'Construction Materials',
            'sub_type' => 'Pavement Materials',
        ],
        [
            'code' => '8202',
            'name' => 'Concrete',
            'type' => 'Construction Materials',
            'sub_type' => 'Pavement Materials',
        ],
        [
            'code' => '8203',
            'name' => 'Recycled Materials',
            'type' => 'Construction Materials',
            'sub_type' => 'Pavement Materials',
        ],

        [
            'code' => '8301',
            'name' => 'Bitumen',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],
        [
            'code' => '8302',
            'name' => 'Cement',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],
        [
            'code' => '8303',
            'name' => 'Water',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],
        [
            'code' => '8304',
            'name' => 'Admixtures',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],
        [
            'code' => '8305',
            'name' => 'Geotextiles',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],
        [
            'code' => '8306',
            'name' => 'Reinforcing Steel',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],
        [
            'code' => '8307',
            'name' => 'Curbs and Gutters',
            'type' => 'Construction Materials',
            'sub_type' => 'Other Materials',
        ],

        [
            'code' => '8401',
            'name' => 'Bamboo',
            'type' => 'Construction Materials',
            'sub_type' => 'Additional Considerations',
        ],
        [
            'code' => '8402',
            'name' => 'Steel Sheet',
            'type' => 'Construction Materials',
            'sub_type' => 'Additional Considerations',
        ],

        [
            'code' => '8501',
            'name' => 'Construction Maintenance',
            'type' => 'Construction Materials',
            'sub_type' => 'Maintenance and Repair',
        ],
        [
            'code' => '8502',
            'name' => 'Construction Repair',
            'type' => 'Construction Materials',
            'sub_type' => 'Maintenance and Repair',
        ],

        [
            'code' => '8601',
            'name' => 'Construction Engineering',
            'type' => 'Construction Materials',
            'sub_type' => 'Engineering',
        ],
        [
            'code' => '8602',
            'name' => 'Construction Planning',
            'type' => 'Construction Materials',
            'sub_type' => 'Engineering',
        ],
        [
            'code' => '8603',
            'name' => 'Construction Estimation',
            'type' => 'Construction Materials',
            'sub_type' => 'Engineering',
        ],
        [
            'code' => '8604',
            'name' => 'Construction Audit',
            'type' => 'Construction Materials',
            'sub_type' => 'Engineering',
        ],
        [
            'code' => '8605',
            'name' => 'Quality Testing',
            'type' => 'Construction Materials',
            'sub_type' => 'Engineering',
        ],
        [
            'code' => '8607',
            'name' => 'Quality Assurance',
            'type' => 'Construction Materials',
            'sub_type' => 'Engineering',
        ],
        // construction materials

        // construction materials
        // construction materials

    );



    // chart of account for new company
    public static function chartOfAccountData1($user)
    {
        $chartOfAccounts = Self::$chartOfAccount1;

        foreach ($chartOfAccounts as $account) {

            $type = ChartOfAccountType::where('created_by', $user)->where('name', $account['type'])->first();
            $sub_type = ChartOfAccountSubType::where('created_by', $user)->where('type', $type->id)->where('name', $account['sub_type'])->first();

            ChartOfAccount::create(
                [
                    'code' => $account['code'],
                    'name' => $account['name'],
                    'type' => $type->id,
                    'sub_type' => $sub_type->id,
                    'is_enabled' => 1,
                    'created_by' => $user,
                ]
            );
        }
    }

    public static function createDefaultUnit($user_id)
    {
        $unit_names = ['Piece', 'Liter', 'KG', 'Ton', 'Bundle', 'Unit', 'Meter', 'KM',];
        foreach ($unit_names as $key => $unit) {
            $create_unit = ProductServiceUnit::create(
                [
                    'name' => $unit,
                    'created_by' => $user_id,
                ]
            );
        }
    }

    public static function createDefaultTax($user_id)
    {
        $create_tax = Tax::create(
            [
                'name' => 'No Tax',
                'rate' => 0,
                'created_by' => $user_id,
            ]
        );
    }

    public static function createDefaultProductCategory($user_id)
    {
        $get_users_coa_subtypes = ChartOfAccountSubType::where('created_by', $user_id)->get();
        foreach ($get_users_coa_subtypes as $key => $coa_subtype) {
            $create_product_category = ProductServiceCategory::create([
                'name' => $coa_subtype->name,
                'type' => ($coa_subtype->type),
                'coa_subtype' => $coa_subtype->id,
                'chart_account_id' => 0,
                'color' => '#FFFFFF',
                'created_by' => $user_id,
            ]);
        }
    }
    public static function createDefaultProduct($user_id)
    {
        $get_users_coa = ChartOfAccount::where('created_by', $user_id)->get();
        foreach ($get_users_coa as $key => $coa) {
            $create_product = ProductService::create([
                'name'=>$coa->name,
                'sku'=>$coa->code,
                'sale_price'=>0,
                'purchase_price'=>0,
                'tax_id'=>Tax::where('created_by',$user_id)->get()->first()->id??1,
                'category_id'=>$coa->type,
                'unit_id'=>ProductServiceUnit::where('created_by',$user_id)->get()->first()->id??1,
                'type'=>1,
                'sale_chartaccount_id'=>$coa->id,
                'expense_chartaccount_id'=>$coa->id,
                'created_by'=>$user_id,
            ]);
        }
    }

    // public static function chartOfAccountData($user)
    // {
    //     $chartOfAccounts = Self::$chartOfAccount1;
    //     foreach ($chartOfAccounts as $account) {
    //         $type = ChartOfAccountType::where('created_by', $user)->where('name', $account['type'])->first();
    //         $sub_type = ChartOfAccountSubType::where('created_by', $user)->where('type', $type->id)->where('name', $account['sub_type'])->first();
    //         if($sub_type){
    //             ChartOfAccount::create(
    //                 [
    //                     'code' => $account['code'],
    //                     'name' => $account['name'],
    //                     'type' => $type->id,
    //                     'sub_type' => $sub_type->id,
    //                     'is_enabled' => 1,
    //                     'created_by' => $user,
    //                 ]
    //             );
    //         }

    //     }
    // }

    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj)
    {
        $usr = Auth::user();
        //Remove Current Login user Email don't send mail to them
        // unset($mailTo[$usr->id]);
        $mailTo = array_values($mailTo);
        if ($usr->type != 'Super Admin') {
            // find template is exist or not in our record
            $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();
            if (isset($template) && !empty($template)) {
                // check template is active or not by company
                if ($usr->type != 'super admin') {
                    $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->where('user_id', '=', $usr->creatorId())->first();
                } else {
                    $is_active = (object) array('is_active' => 1);
                }
                if ($is_active->is_active == 1) {

                    $settings = self::settingsById($usr->id);
                    // get email content language base
                    $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
                    $content->from = $template->from;
                    if (!empty($content->content)) {
                        $content->content = self::replaceVariable($content->content, $obj);
                        // send email
                        try {
                            config(
                                [
                                    'mail.driver'       => $settings['mail_driver'],
                                    'mail.host'         => $settings['mail_host'],
                                    'mail.port'         => $settings['mail_port'],
                                    'mail.encryption'   => $settings['mail_encryption'],
                                    'mail.username'     => $settings['mail_username'],
                                    'mail.password'     => $settings['mail_password'],
                                    'mail.from.address' => $settings['mail_from_address'],
                                    'mail.from.name'    => $settings['mail_from_name'],
                                ]
                            );
                            Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings));
                        } catch (\Exception $e) {
                            $error = $e->getMessage();
                        }

                        if (isset($error)) {
                            $arReturn = [
                                'is_success' => false,
                                'error' => $error,
                            ];
                        } else {
                            $arReturn = [
                                'is_success' => true,
                                'error' => false,
                            ];
                        }
                    } else {
                        $arReturn = [
                            'is_success' => false,
                            'error' => __('Mail not send, email is empty'),
                        ];
                    }

                    return $arReturn;
                } else {
                    return [
                        'is_success' => true,
                        'error' => false,
                    ];
                }
            } else {
                return [
                    'is_success' => false,
                    'error' => __('Mail not send, email not found'),
                ];
            }
        }
    }


    public static function sendUserEmailTemplate($emailTemplate, $mailTo, $obj)
    {
        $usr = Auth::user();
        //Remove Current Login user Email don't send mail to them
        // unset($mailTo[$usr->id]);
        $mailTo = array_values($mailTo);

        // find template is exist or not in our record
        $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();
        if (isset($template) && !empty($template)) {
            // check template is active or not by company

            $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->where('user_id', '=', $usr->creatorId())->first();

            if ($is_active->is_active == 1) {

                $settings = self::settingsById(1);

                // get email content language base
                $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
                $content->from = $template->from;
                if (!empty($content->content)) {
                    $content->content = self::replaceVariable($content->content, $obj);
                    // send email
                    try {
                        config(
                            [
                                'mail.driver'       => $settings['mail_driver'],
                                'mail.host'         => $settings['mail_host'],
                                'mail.port'         => $settings['mail_port'],
                                'mail.encryption'   => $settings['mail_encryption'],
                                'mail.username'     => $settings['mail_username'],
                                'mail.password'     => $settings['mail_password'],
                                'mail.from.address' => $settings['mail_from_address'],
                                'mail.from.name'    => $settings['mail_from_name'],
                            ]
                        );
                        Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings));
                    } catch (\Exception $e) {
                        $error = $e->getMessage();
                    }

                    if (isset($error)) {
                        $arReturn = [
                            'is_success' => false,
                            'error' => $error,
                        ];
                    } else {
                        $arReturn = [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                } else {
                    $arReturn = [
                        'is_success' => false,
                        'error' => __('Mail not send, email is empty'),
                    ];
                }

                return $arReturn;
            } else {
                return [
                    'is_success' => true,
                    'error' => false,
                ];
            }
        } else {
            return [
                'is_success' => false,
                'error' => __('Mail not send, email not found'),
            ];
        }
    }

    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{app_name}',
            '{company_name}',
            '{app_url}',
            '{email}',
            '{password}',
            '{client_name}',
            '{client_email}',
            '{client_password}',
            '{support_name}',
            '{support_title}',
            '{support_priority}',
            '{support_end_date}',
            '{support_description}',
            '{lead_name}',
            '{lead_email}',
            '{lead_subject}',
            '{lead_pipeline}',
            '{lead_stage}',
            '{deal_name}',
            '{deal_pipeline}',
            '{deal_stage}',
            '{deal_status}',
            '{deal_price}',
            '{award_name}',
            '{award_email}',
            '{customer_name}',
            '{customer_email}',
            '{invoice_name}',
            '{invoice_number}',
            '{invoice_url}',
            '{invoice_payment_name}',
            '{invoice_payment_amount}',
            '{invoice_payment_date}',
            '{payment_dueAmount}',
            '{payment_reminder_name}',
            '{invoice_payment_number}',
            '{invoice_payment_dueAmount}',
            '{payment_reminder_date}',
            '{payment_name}',
            '{payment_bill}',
            '{payment_amount}',
            '{payment_date}',
            '{payment_method}',
            '{vender_name}',
            '{vender_email}',
            '{bill_name}',
            '{bill_number}',
            '{bill_url}',
            '{proposal_name}',
            '{proposal_number}',
            '{proposal_url}',
            '{complaint_name}',
            '{complaint_title}',
            '{complaint_against}',
            '{complaint_date}',
            '{complaint_description}',
            '{leave_name}',
            '{leave_status}',
            '{leave_reason}',
            '{leave_start_date}',
            '{leave_end_date}',
            '{total_leave_days}',
            '{employee_name}',
            '{employee_email}',
            '{payslip_name}',
            '{payslip_salary_month}',
            '{payslip_url}',
            '{promotion_designation}',
            '{promotion_title}',
            '{promotion_date}',
            '{resignation_email}',
            '{assign_user}',
            '{resignation_date}',
            '{notice_date}',
            '{termination_name}',
            '{termination_email}',
            '{termination_date}',
            '{termination_type}',
            '{transfer_name}',
            '{transfer_email}',
            '{transfer_date}',
            '{transfer_department}',
            '{transfer_branch}',
            '{transfer_description}',
            '{trip_name}',
            '{purpose_of_visit}',
            '{start_date}',
            '{end_date}',
            '{place_of_visit}',
            '{trip_description}',
            '{vender_bill_name}',
            '{vender_bill_number}',
            '{vender_bill_url}',
            '{employee_warning_name}',
            '{warning_subject}',
            '{warning_description}',
            '{contract_client}',
            '{contract_subject}',
            '{contract_start_date}',
            '{contract_end_date}',
            '{user_name}',
            '{lead_user_name}',
            '{project_name}',
            '{payment_price}',
            '{invoice_payment_type}',
            '{task_name}',
            '{old_stage_name}',
            '{new_stage_name}',
            '{year}',
            '{announcement_title}',
            '{branch_name}',
            '{support_user_name}',
            '{meeting_title}',
            '{meeting_date}',
            '{meeting_time}',
            '{award_date}',
            '{holiday_title}',
            '{holiday_date}',
            '{event_title}',
            '{event_start_date}',
            '{event_end_date}',
            '{company_policy_name}',
            '{budget_period}',
            '{budget_year}',
            '{budget_name}',
            '{revenue_amount}',
            '{vendor_name}',
            '{payment_type}',
            '{bill_due_date}',
            '{bill_date}',




        ];
        $arrValue    = [
            'app_name' => '-',
            'company_name' => '-',
            'app_url' => '-',
            'email' => '-',
            'password' => '-',
            'client_name' => '-',
            'client_email' => '-',
            'client_password' => '-',
            'support_name' => '-',
            'support_title' => '-',
            'support_priority' => '-',
            'support_end_date' => '-',
            'support_description' => '-',
            'lead_name' => '-',
            'lead_email' => '-',
            'lead_subject' => '-',
            'lead_pipeline' => '-',
            'lead_stage' => '-',
            'deal_name' => '-',
            'deal_pipeline' => '-',
            'deal_stage' => '-',
            'deal_status' => '-',
            'deal_price' => '-',
            'award_name' => '-',
            'award_email' => '-',
            'customer_name' => '-',
            'customer_email' => '-',
            'invoice_name' => '-',
            'invoice_number' => '-',
            'invoice_url' => '-',
            'invoice_payment_name' => '-',
            'invoice_payment_amount' => '-',
            'invoice_payment_date' => '-',
            'payment_dueAmount' => '-',
            'payment_reminder_name' => '-',
            'invoice_payment_number' => '-',
            'invoice_payment_dueAmount' => '-',
            'payment_reminder_date' => '-',
            'payment_name' => '-',
            'payment_bill' => '-',
            'payment_amount' => '-',
            'payment_date' => '-',
            'payment_method' => '-',
            'vender_name' => '-',
            'vender_email' => '-',
            'bill_name' => '-',
            'bill_number' => '-',
            'bill_url' => '-',
            'proposal_name' => '-',
            'proposal_number' => '-',
            'proposal_url' => '-',
            'complaint_name' => '-',
            'complaint_title' => '-',
            'complaint_against' => '-',
            'complaint_date' => '-',
            'complaint_description' => '-',
            'leave_name' => '-',
            'leave_status' => '-',
            'leave_reason' => '-',
            'leave_start_date' => '-',
            'leave_end_date' => '-',
            'total_leave_days' => '-',
            'employee_name' => '-',
            'employee_email' => '-',
            'payslip_name' => '-',
            'payslip_salary_month' => '-',
            'payslip_url' => '-',
            'promotion_designation' => '-',
            'promotion_title' => '-',
            'promotion_date' => '-',
            'resignation_email' => '-',
            'assign_user' => '-',
            'resignation_date' => '-',
            'notice_date' => '-',
            'termination_name' => '-',
            'termination_email' => '-',
            'termination_date' => '-',
            'termination_type' => '-',
            'transfer_name' => '-',
            'transfer_email' => '-',
            'transfer_date' => '-',
            'transfer_department' => '-',
            'transfer_branch' => '-',
            'transfer_description' => '-',
            'trip_name' => '-',
            'purpose_of_visit' => '-',
            'start_date' => '-',
            'end_date' => '-',
            'place_of_visit' => '-',
            'trip_description' => '-',
            'vender_bill_name' => '-',
            'vender_bill_number' => '-',
            'vender_bill_url' => '-',
            'employee_warning_name' => '-',
            'warning_subject' => '-',
            'warning_description' => '-',
            'contract_client' => '-',
            'contract_subject' => '-',
            'contract_start_date' => '-',
            'contract_end_date' => '-',
            'user_name' => '-',
            'lead_user_name' => '-',
            'project_name' => '-',
            'payment_price' => '-',
            'invoice_payment_type' => '-',
            'task_name' => '-',
            'old_stage_name' => '-',
            'new_stage_name' => '-',
            'year' => '-',
            'announcement_title' => '-',
            'branch_name' => '-',
            'support_user_name' => '-',
            'meeting_title' => '-',
            'meeting_date' => '-',
            'meeting_time' => '-',
            'award_date' => '-',
            'holiday_title' => '-',
            'holiday_date' => '-',
            'event_title' => '-',
            'event_start_date' => '-',
            'event_end_date' => '-',
            'company_policy_name' => '-',
            'budget_period' => '-',
            'budget_year' => '-',
            'budget_name' => '-',
            'revenue_amount' => '-',
            'vendor_name' => '-',
            'payment_type' => '-',
            'bill_due_date' => '-',
            'bill_date' => '-',
        ];


        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }

        //        dd($obj);
        $settings = Utility::settings();
        $company_name = $settings['company_name'];

        $arrValue['app_name']     =  !empty($company_name) ? $company_name : env('APP_NAME');
        $arrValue['company_name'] = self::settings()['mail_from_name'];
        $arrValue['app_url']      = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    public static function pipeline_lead_deal_Stage($created_id)
    {
        $pipeline = Pipeline::create(
            [
                'name' => 'Sales',
                'created_by' => $created_id,
            ]
        );
        $stages   = [
            'Draft',
            'Sent',
            'Open',
            'Revised',
            'Declined',
        ];
        foreach ($stages as $stage) {
            LeadStage::create(
                [
                    'name' => $stage,
                    'pipeline_id' => $pipeline->id,
                    'created_by' => $created_id,
                ]
            );
            Stage::create(
                [
                    'name' => $stage,
                    'pipeline_id' => $pipeline->id,
                    'created_by' => $created_id,
                ]
            );
        }
    }

    public static function project_task_stages($created_id)
    {
        $projectStages = [
            'To Do',
            'In Progress',
            'Review',
            'Done',
        ];
        foreach ($projectStages as $key => $stage) {
            TaskStage::create(
                [
                    'name' => $stage,
                    'order' => $key,
                    'created_by' => $created_id,
                ]
            );
        }
    }

    public static function labels($created_id)
    {
        $stages = [
            [
                'name' => 'On Hold',
                'color' => 'primary',
            ],
            [
                'name' => 'New',
                'color' => 'info',
            ],
            [
                'name' => 'Pending',
                'color' => 'warning',
            ],
            [
                'name' => 'Loss',
                'color' => 'danger',
            ],
            [
                'name' => 'Win',
                'color' => 'success',
            ],
        ];
        foreach ($stages as $stage) {
            Label::create(
                [
                    'name' => $stage['name'],
                    'color' => $stage['color'],
                    'pipeline_id' => 1,
                    'created_by' => $created_id,
                ]
            );
        }
        $bugStatus = [
            'Confirmed',
            'Resolved',
            'Unconfirmed',
            'In Progress',
            'Verified',
        ];
        foreach ($bugStatus as $status) {
            BugStatus::create(
                [
                    'title' => $status,
                    'created_by' => $created_id,
                ]
            );
        }
    }

    public static function sources($created_id)
    {
        $stages = [
            'Websites',
            'Facebook',
            'bdJobs.com',
            'Phone',
            'LinkedIn',
        ];
        foreach ($stages as $stage) {
            Source::create(
                [
                    'name' => $stage,
                    'created_by' => $created_id,
                ]
            );
        }
    }

    public static function employeeNumber($user_id)
    {
        $latest = Employee::where('created_by', $user_id)->latest()->first();

        if (!$latest) {
            return 1;
        }

        return $latest->employee_id + 1;
    }

    public static function employeeDetails($user_id, $created_by)
    {
        $user = User::where('id', $user_id)->first();

        $employee = Employee::create(
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'employee_id' => Utility::employeeNumber($created_by),
                'created_by' => $created_by,
            ]
        );
    }

    public static function employeeDetailsUpdate($user_id, $created_by)
    {
        $user = User::where('id', $user_id)->first();

        $employee = Employee::where('user_id', $user->id)->update(
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
    }

    public static function jobStage($id)
    {
        $stages = [
            'Applied',
            'Phone Screen',
            'Interview',
            'Hired',
            'Rejected',
        ];
        foreach ($stages as $stage) {

            JobStage::create(
                [
                    'title' => $stage,
                    'created_by' => $id,
                ]
            );
        }
    }

    public static function errorFormat($errors)
    {
        $err = '';

        foreach ($errors->all() as $msg) {
            $err .= $msg . '<br>';
        }

        return $err;
    }

    // get date formated
    public static function getDateFormated($date)
    {
        if (!empty($date) && $date != '0000-00-00') {
            return date("d M Y", strtotime($date));
        } else {
            return '';
        }
    }

    // get progress bar color
    public static function getProgressColor($percentage)
    {

        $color = '';

        if ($percentage <= 20) {
            $color = 'danger';
        } elseif ($percentage > 20 && $percentage <= 40) {
            $color = 'warning';
        } elseif ($percentage > 40 && $percentage <= 60) {
            $color = 'info';
        } elseif ($percentage > 60 && $percentage <= 80) {
            $color = 'secondary';
        } elseif ($percentage >= 80) {
            $color = 'primary';
        }

        return $color;
    }

    // Return Percentage from two value
    public static function getPercentage($val1 = 0, $val2 = 0)
    {
        $percentage = 0;
        if ($val1 > 0 && $val2 > 0) {
            $percentage = intval(($val1 / $val2) * 100);
        }

        return $percentage;
    }

    // For crm dashboard
    public static function getCrmPercentage($val1 = 0, $val2 = 0)
    {
        $percentage = 0;
        if ($val1 > 0 && $val2 > 0) {
            $percentage = ($val1 / $val2) * 100;
            $percentage = number_format($percentage, \Utility::getValByName('decimal_number'));
        }

        return $percentage;
    }

    public static function timeToHr($times)
    {
        $totaltime = self::calculateTimesheetHours($times);
        $timeArray = explode(':', $totaltime);
        if ($timeArray[1] <= '30') {
            $totaltime = $timeArray[0];
        }
        $totaltime = $totaltime != '00' ? $totaltime : '0';

        return $totaltime;
    }

    public static function calculateTimesheetHours($times)
    {
        $minutes = 0;
        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }
        $hours   = floor($minutes / 60);
        $minutes -= $hours * 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    // Return Last 7 Days with date & day name
    public static function getLastSevenDays()
    {
        $arrDuration   = [];
        $previous_week = strtotime("-1 week +1 day");

        for ($i = 0; $i < 7; $i++) {
            $arrDuration[date('Y-m-d', $previous_week)] = date('D', $previous_week);
            $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
        }

        return $arrDuration;
    }

    // Check File is exist and delete these
    public static function checkFileExistsnDelete(array $files)
    {
        $status = false;
        foreach ($files as $key => $file) {
            if (Storage::exists($file)) {
                $status = Storage::delete($file);
            }
        }

        return $status;
    }

    // get project wise currency formatted amount
    public static function projectCurrencyFormat($project_id, $amount, $decimal = false)
    {
        $project = Project::find($project_id);
        if (empty($project)) {
            $settings = Utility::settings();

            return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($amount, Utility::getValByName('decimal_number')) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
        }
    }

    // Return Week first day and last day
    public static function getFirstSeventhWeekDay($week = null)
    {
        $first_day = $seventh_day = null;
        if (isset($week)) {
            $first_day   = Carbon::now()->addWeeks($week)->startOfWeek();
            $seventh_day = Carbon::now()->addWeeks($week)->endOfWeek();
        }
        $dateCollection['first_day']   = $first_day;
        $dateCollection['seventh_day'] = $seventh_day;
        $period                        = CarbonPeriod::create($first_day, $seventh_day);
        foreach ($period as $key => $dateobj) {
            $dateCollection['datePeriod'][$key] = $dateobj;
        }

        return $dateCollection;
    }

    //    public static function employeePayslipDetail($employeeId)
    //    {
    ////        dd($employeeId);
    //        $earning['allowance']         = Allowance::where('employee_id', $employeeId)->get();
    ////        dd($earning['allowance']);
    //        $employeesSalary = Employee::find($employeeId);
    //
    //        $totalAllowance = 0 ;
    //        foreach($earning['allowance'] as $allowance)
    //        {
    //            if($allowance->type == 'fixed')
    //            {
    //                $totalAllowances  = $allowance->amount;
    //            }
    //            else
    //            {
    //                $totalAllowances  = $allowance->amount * $employeesSalary->salary / 100;
    //            }
    //            $totalAllowance += $totalAllowances ;
    //        }
    //
    //
    ////        $earning['totalAllowance']    = Allowance::where('employee_id', $employeeId)->where('type', 'fixed')->get()->sum('amount');
    //        $earning['commission']        = Commission::where('employee_id', $employeeId)->get();
    //        $totalCommisions = 0 ;
    //        foreach($earning['commission'] as $commission)
    //        {
    //            if($commission->type == 'fixed')
    //            {
    //                $totalCom  = $commission->amount;
    //            }
    //            else
    //            {
    //                $totalCom  = $commission->amount * $employeesSalary->salary / 100;
    //            }
    //            $totalCommisions += $totalCom ;
    //        }
    ////        $earning['totalCommission']   = Commission::where('employee_id', $employeeId)->where('type', 'fixed')->get()->sum('amount');
    //        $earning['otherPayment']      = OtherPayment::where('employee_id', $employeeId)->get();
    //        $totalOtherPayment = 0 ;
    //        foreach($earning['otherPayment'] as $otherPayment)
    //        {
    //            if($otherPayment->type == 'fixed')
    //            {
    //                $totalother  = $otherPayment->amount;
    //            }
    //            else
    //            {
    //                $totalother  = $otherPayment->amount * $employeesSalary->salary / 100;
    //            }
    //            $totalOtherPayment += $totalother ;
    //        }
    ////        $earning['totalOtherPayment'] = OtherPayment::where('employee_id', $employeeId)->where('type', 'fixed')->get()->sum('amount');
    //        $earning['overTime']          = Overtime::select('id', 'title')->selectRaw('number_of_days * hours* rate as amount')->where('employee_id', $employeeId)->get();
    //        $earning['totalOverTime']     = Overtime::selectRaw('number_of_days * hours* rate as total')->where('employee_id', $employeeId)->get()->sum('total');
    //
    //        $deduction['loan']           = Loan::where('employee_id', $employeeId)->get();
    //        $totalLoan = 0 ;
    //        foreach($deduction['loan'] as $loan)
    //        {
    //            if($loan->type == 'fixed')
    //            {
    //                $totalloan  = $loan->amount;
    //            }
    //            else
    //            {
    //                $totalloan  = $loan->amount * $employeesSalary->salary / 100;
    //            }
    //            $totalLoan += $totalloan ;
    //        }
    ////        $deduction['totalLoan']      = Loan::where('employee_id', $employeeId)->where('type', 'fixed')->get()->sum('amount');
    //        $deduction['deduction']      = SaturationDeduction::where('employee_id', $employeeId)->get();
    //        $totalDeduction = 0 ;
    //        foreach($deduction['deduction'] as $deductions)
    //        {
    //            if($deductions->type == 'fixed')
    //            {
    //                $totaldeduction  = $deductions->amount;
    //            }
    //            else
    //            {
    //                $totaldeduction  = $deductions->amount * $employeesSalary->salary / 100;
    //            }
    //            $totalDeduction += $totaldeduction ;
    //        }
    ////        $deduction['totalDeduction'] = SaturationDeduction::where('employee_id', $employeeId)->where('type', 'fixed')->get()->sum('amount');
    //
    //        $payslip['earning']        = $earning;
    //        $payslip['totalEarning']   = $totalAllowance + $totalCommisions + $totalOtherPayment + $earning['totalOverTime'];
    //        $payslip['deduction']      = $deduction;
    //        $payslip['totalDeduction'] = $totalLoan + $totalDeduction;
    //
    //        return $payslip;
    //    }

    public static function employeePayslipDetail($employeeId, $month)
    {
        // allowance
        $earning['allowance'] = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();

        $employess = Employee::find($employeeId);

        $totalAllowance = 0;

        $arrayJson = json_decode($earning['allowance']);
        foreach ($arrayJson as $earn) {
            // dd($earn->basic_salary);
            $allowancejson = json_decode($earn->allowance);
            foreach ($allowancejson as $allowances) {
                if ($allowances->type == 'percentage') {
                    $empall  = $allowances->amount * $earn->basic_salary / 100;
                } else {
                    $empall = $allowances->amount;
                }
                $totalAllowance += $empall;
            }
        }

        // commission
        $earning['commission'] = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();

        $employess = Employee::find($employeeId);

        $totalCommission = 0;

        $arrayJson = json_decode($earning['commission']);

        foreach ($arrayJson as $earn) {
            $commissionjson = json_decode($earn->commission);

            foreach ($commissionjson as $commissions) {

                if ($commissions->type == 'percentage') {
                    $empcom  = $commissions->amount * $earn->basic_salary / 100;
                } else {
                    $empcom = $commissions->amount;
                }
                $totalCommission += $empcom;
            }
        }

        // otherpayment
        $earning['otherPayment']      = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();

        $employess = Employee::find($employeeId);

        $totalotherpayment = 0;

        $arrayJson = json_decode($earning['otherPayment']);

        foreach ($arrayJson as $earn) {
            $otherpaymentjson = json_decode($earn->other_payment);

            foreach ($otherpaymentjson as $otherpay) {
                if ($otherpay->type == 'percentage') {
                    $empotherpay  = $otherpay->amount * $earn->basic_salary / 100;
                } else {
                    $empotherpay = $otherpay->amount;
                }
                $totalotherpayment += $empotherpay;
            }
        }

        //overtime
        $earning['overTime'] = Payslip::where('employee_id', $employeeId)->where('salary_month', $month)->get();

        $ot = 0;

        $arrayJson = json_decode($earning['overTime']);
        foreach ($arrayJson as $overtime) {
            $overtimes = json_decode($overtime->overtime);
            foreach ($overtimes as $overt) {
                $OverTime = $overt->number_of_days * $overt->hours * $overt->rate;
                $ot += $OverTime;
            }
        }

        // loan
        $deduction['loan'] = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();

        $employess = Employee::find($employeeId);

        $totalloan = 0;

        $arrayJson = json_decode($deduction['loan']);

        foreach ($arrayJson as $loan) {
            $loans = json_decode($loan->loan);

            foreach ($loans as $emploans) {

                if ($emploans->type == 'percentage') {
                    $emploan  = $emploans->amount * $loan->basic_salary / 100;
                } else {
                    $emploan = $emploans->amount;
                }
                $totalloan += $emploan;
            }
        }

        // saturation_deduction
        $deduction['deduction']      = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();

        $employess = Employee::find($employeeId);

        $totaldeduction = 0;

        $arrayJson = json_decode($deduction['deduction']);

        foreach ($arrayJson as $deductions) {
            // dd($deductions->basic_salary);
            $deduc = json_decode($deductions->saturation_deduction);
            foreach ($deduc as $deduction_option) {
                if ($deduction_option->type == 'percentage') {
                    $empdeduction  = $deduction_option->amount * $deductions->basic_salary / 100;
                } else {
                    $empdeduction = $deduction_option->amount;
                }
                $totaldeduction += $empdeduction;
            }
        }

        $payslip['earning']        = $earning;
        $payslip['totalEarning']   = $totalAllowance + $totalCommission + $totalotherpayment + $ot;
        $payslip['deduction']      = $deduction;
        $payslip['totalDeduction'] = $totalloan + $totaldeduction;

        return $payslip;
    }

    public static function companyData($company_id, $string)
    {
        $setting = DB::table('settings')->where('created_by', $company_id)->where('name', $string)->first();
        if (!empty($setting)) {
            return $setting->value;
        } else {
            return '';
        }
    }

    public static function addNewData()
    {
        \Artisan::call('cache:forget spatie.permission.cache');
        \Artisan::call('cache:clear');
        $usr = \Auth::user();

        $arrPermissions = [
            'manage form builder',
            'create form builder',
            'edit form builder',
            'delete form builder',
            'manage form field',
            'create form field',
            'edit form field',
            'delete form field',
            'view form response',
            'manage performance type',
            'create performance type',
            'edit performance type',
            'delete performance type',
            'manage budget plan',
            'create budget plan',
            'edit budget plan',
            'delete budget plan',
            'view budget plan',
            'stock report',
            'manage warehouse',
            'create warehouse',
            'edit warehouse',
            'show warehouse',
            'delete warehouse',
            'manage purchase',
            'create purchase',
            'edit purchase',
            'show purchase',
            'delete purchase',
            'send purchase',
            'create payment purchase',
            'manage pos',
            'manage contract type',
            'create contract type',
            'edit contract type',
            'delete contract type',
            'create barcode',
            'show crm dashboard',
            'share project',
            'show pos dashboard',
            'create webhook',
            'edit webhook',
            'delete webhook',

        ];
        foreach ($arrPermissions as $ap) {
            // check if permission is not created then create it.
            $permission = Permission::where('name', 'LIKE', $ap)->first();
            if (empty($permission)) {
                Permission::create(['name' => $ap]);
            }
        }
        $companyRole = Role::where('name', 'LIKE', 'company')->first();

        $companyPermissions   = $companyRole->getPermissionNames()->toArray();
        $companyNewPermission = [
            'manage form builder',
            'create form builder',
            'edit form builder',
            'delete form builder',
            'manage form field',
            'create form field',
            'edit form field',
            'delete form field',
            'view form response',
            'manage performance type',
            'create performance type',
            'edit performance type',
            'delete performance type',
            'manage budget plan',
            'create budget plan',
            'edit budget plan',
            'delete budget plan',
            'view budget plan',
            'stock report',
            'manage warehouse',
            'create warehouse',
            'edit warehouse',
            'show warehouse',
            'delete warehouse',
            'manage purchase',
            'create purchase',
            'edit purchase',
            'show purchase',
            'delete purchase',
            'send purchase',
            'create payment purchase',
            'manage pos',
            'manage contract type',
            'create contract type',
            'edit contract type',
            'delete contract type',
            'create barcode',
            'show crm dashboard',
            'share project',
            'show pos dashboard',
            'create webhook',
            'edit webhook',
            'delete webhook',
        ];
        foreach ($companyNewPermission as $op) {
            // check if permission is not assign to owner then assign.
            if (!in_array($op, $companyPermissions)) {
                $permission = Permission::findByName($op);
                $companyRole->givePermissionTo($permission);
            }
        }
    }

    public static function getAdminPaymentSetting()
    {

        $data     = \DB::table('admin_payment_settings');

        $settings = [];
        if (\Auth::check()) {

            $user_id = 1;
            $data    = $data->where('created_by', '=', $user_id);
        }
        $data = $data->get();
        //        dd($data);
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function getCompanyPaymentSetting($user_id)
    {

        $data     = \DB::table('company_payment_settings');
        $settings = [];
        $data     = $data->where('created_by', '=', $user_id);
        $data     = $data->get();

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }



        return $settings;
    }

    public static function getCompanyPayment()
    {

        $data     = \DB::table('company_payment_settings');
        $settings = [];
        if (\Auth::check()) {
            $user_id = \Auth::user()->creatorId();
            $data    = $data->where('created_by', '=', $user_id);
        }
        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }



    public static function error_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "error" : $msg;
        $msg_id    = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }

    public static function success_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "success" : $msg;
        $msg_id    = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }

    public static function get_messenger_packages_migration()
    {
        $totalMigration = 0;
        $messengerPath  = glob(base_path() . '/vendor/munafio/chatify/database/migrations' . DIRECTORY_SEPARATOR . '*.php');
        if (!empty($messengerPath)) {
            $messengerMigration = str_replace('.php', '', $messengerPath);
            $totalMigration     = count($messengerMigration);
        }

        return $totalMigration;
    }

    public static function getselectedThemeColor()
    {
        $color = env('THEME_COLOR');
        if ($color == "" || $color == null) {
            $color = 'blue';
        }

        return $color;
    }

    public static function getAllThemeColors()
    {
        $colors = [
            'blue',
            'denim',
            'sapphire',
            'olympic',
            'violet',
            'black',
            'cyan',
            'dark-blue-natural',
            'gray-dark',
            'light-blue',
            'light-purple',
            'magenta',
            'orange-mute',
            'pale-green',
            'rich-magenta',
            'rich-red',
            'sky-gray',
        ];

        return $colors;
    }

    public static function diffance_to_time($start, $end)
    {
        $start         = new Carbon($start);
        $end           = new Carbon($end);
        $totalDuration = $start->diffInSeconds($end);
        return $totalDuration;
    }

    public static function second_to_time($seconds = 0)
    {
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;

        $time = sprintf("%02d:%02d:%02d", $H, $i, $s);

        return $time;
    }


    //Slack notification
    public static function send_slack_msg($slug, $obj, $user_id = null)
    {

        $notification_template = NotificationTemplates::where('slug', $slug)->first();

        if (!empty($notification_template) && !empty($obj)) {
            if (!empty($user_id)) {
                $user = User::find($user_id);
            } else {
                $user = \Auth::user();
            }
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->where('created_by', '=', $user->id)->first();

            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }

        //dd($msg);
        if (isset($msg)) {
            $settings = Utility::settingsById($user->id);
            try {
                if (isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            } catch (\Exception $e) {
            }
        }
    }

    //Telegram Notification
    public static function send_telegram_msg($slug, $obj, $user_id = null)
    {
        $notification_template = NotificationTemplates::where('slug', $slug)->first();

        if (!empty($notification_template) && !empty($obj)) {
            if (!empty($user_id)) {
                $user = User::find($user_id);
            } else {
                $user = \Auth::user();
            }
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->where('created_by', '=', $user->id)->first();

            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }


        if (isset($msg)) {
            $settings = Utility::settingsById($user->id);
            try {
                if (isset($settings['telegram_accestoken']) && !empty($settings['telegram_accestoken'])) {
                    // Set your Bot ID and Chat ID.
                    $telegrambot = $settings['telegram_accestoken'];
                    $telegramchatid = $settings['telegram_chatid'];
                    // Function call with your own text or variable
                    $url = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
                    $data = array(
                        'chat_id' => $telegramchatid,
                        'text' => $msg,
                    );
                    $options = array(
                        'http' => array(
                            'method' => 'POST',
                            'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                            'content' => http_build_query($data),
                        ),
                    );
                    $context = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);
                    $url = $url;
                }
            } catch (\Exception $e) {
            }
        }
    }

    //Twilio Notification
    public static function send_twilio_msg($to, $slug, $obj, $user_id = null)
    {

        $notification_template = NotificationTemplates::where('slug', $slug)->first();

        if (!empty($notification_template) && !empty($obj)) {
            if (!empty($user_id)) {
                $user = User::find($user_id);
            } else {
                $user = \Auth::user();
            }
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->where('created_by', '=', $user->id)->first();

            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }

        //        dd($msg);

        if (isset($msg)) {
            $settings = Utility::settingsById($user->id);
            $account_sid = $settings['twilio_sid'];
            $auth_token = $settings['twilio_token'];
            $twilio_number = $settings['twilio_from'];
            try {
                $client = new Client($account_sid, $auth_token);
                $client->messages->create($to, [
                    'from' => $twilio_number,
                    'body' => $msg,
                ]);
            } catch (\Exception $e) {
            }
        }
    }

    //inventory management (Quantity)
    public static function total_quantity($type, $quantity, $product_id)
    {
        $product      = ProductService::find($product_id);

        if (($product->type == 'product')) {
            $pro_quantity = $product->quantity;


            if ($type == 'minus') {
                $product->quantity = $pro_quantity - $quantity;
            } else {
                $product->quantity = $pro_quantity + $quantity;
            }
            $product->save();
        }
    }

    //quantity update in warehouse details
    public static function warehouse_quantity($type, $quantity, $product_id, $warehouse_id)
    {
        $product      = WarehouseProduct::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();

        $pro_quantity = (!empty($product) && !empty($product->quantity)) ? $product->quantity : 0;

        if ($type == 'minus') {
            $product->quantity = $pro_quantity != 0 ? $pro_quantity - $quantity : $quantity;
        } else {
            $product->quantity = $pro_quantity + $quantity;
        }
        $product->save();
    }

    //warehouse transfer
    public static function warehouse_transfer_qty($from_warehouse, $to_warehouse, $product_id, $quantity, $delete = null)
    {

        $toWarehouse      = WarehouseProduct::where('warehouse_id', $to_warehouse)->where('product_id', $product_id)->first();
        if (empty($toWarehouse)) {
            if ($delete != 'delete') {
                $transfer                = new WarehouseProduct();
                $transfer->warehouse_id  = $to_warehouse;
                $transfer->product_id    = $product_id;
                $transfer->quantity      = $quantity;
                $transfer->created_by    = \Auth::user()->creatorId();
                $transfer->save();
            }
        } else {
            $toWarehouse->quantity   = $toWarehouse->quantity + $quantity;
            $toWarehouse->save();
        }
        $fromWarehouse               = WarehouseProduct::where('warehouse_id', $from_warehouse)->where('product_id', $product_id)->first();
        if (!empty($fromWarehouse)) {
            $fromWarehouse->quantity     = ($fromWarehouse->quantity) - ($quantity);
            if ($fromWarehouse->quantity <= 0) {
                $fromWarehouse->delete();
            } else {
                $fromWarehouse->save();
            }
        }
    }

    //add quantity in product stock
    public static function addProductStock($product_id, $quantity, $type, $description, $type_id)
    {

        $stocks             = new StockReport();
        $stocks->product_id = $product_id;
        $stocks->quantity     = $quantity;
        $stocks->type = $type;
        $stocks->type_id = $type_id;
        $stocks->description = $description;
        $stocks->created_by = \Auth::user()->creatorId();
        $stocks->save();
    }

    public static function g()
    {
        $data = DB::table('settings');

        if (\Auth::check()) {

            $data = $data->where('created_by', '=', \Auth::user()->creatorId())->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        } else {

            $data->where('created_by', '=', 1);
            $data = $data->get();
        }

        $settings = [
            "cust_darklayout" => "off",
            "cust_theme_bg" => "on",
            "color" => ''
        ];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function colorset()
    {
        if (\Auth::check()) {
            if (\Auth::user()->type == 'super admin') {
                $user = \Auth::user();
                $setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
            } else {
                $setting = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->pluck('value', 'name')->toArray();
            }
        } else {
            $user = User::where('type', 'super admin')->first();
            $setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
        }

        if (!isset($setting['color'])) {
            $setting = Utility::settings();
        }


        return $setting;
    }

    public static function getSeoSetting()
    {
        $data = \DB::table('settings')->whereIn('name', ['meta_title', 'meta_desc', 'meta_image'])->get();
        $settings = [];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function get_superadmin_logo()
    {
        $is_dark_mode = self::getValByName('cust_darklayout');
        $setting = DB::table('settings')->where('created_by', Auth::user()->id)->pluck('value', 'name')->toArray();
        if (!empty($setting['cust_darklayout'])) {
            $is_dark_mode = $setting['cust_darklayout'];
            // dd($is_dark_mode);
            if ($is_dark_mode == 'on') {
                return 'logo-light.png';
            } else {
                return 'logo-dark.png';
            }
        } else {
            return 'logo-dark.png';
        }
    }

    public static function GetLogo()
    {
        $setting = Utility::colorset();

        if (\Auth::user() && \Auth::user()->type != 'super admin') {

            if (Utility::getValByName('cust_darklayout') == 'on') {

                return Utility::getValByName('company_logo_light');
            } else {
                return Utility::getValByName('company_logo_dark');
            }
        } else {
            if (Utility::getValByName('cust_darklayout') == 'on') {

                return Utility::getValByName('light_logo');
            } else {
                return Utility::getValByName('dark_logo');
            }
        }
    }

    public static function getGdpr()
    {
        $data = DB::table('settings');
        if (\Auth::check()) {
            $data = $data->where('created_by', '=', 1);
        } else {
            $data = $data->where('created_by', '=', 1);
        }
        $data     = $data->get();
        $settings = [
            "gdpr_cookie" => "",
            "cookie_text" => "",
        ];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function getValByName1($key)
    {
        $setting = Utility::getGdpr();
        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }
        return $setting[$key];
    }


    //add quantity in warehouse stock
    public static function addWarehouseStock($product_id, $quantity, $warehouse_id)
    {


        $product     = WarehouseProduct::where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();

        if ($product) {
            $pro_quantity = $product->quantity;
            $product_quantity = $pro_quantity + $quantity;
        } else {
            $product_quantity = $quantity;
        }

        $data = WarehouseProduct::updateOrCreate(
            ['warehouse_id' => $warehouse_id, 'product_id' => $product_id, 'created_by' => \Auth::user()->id],
            ['warehouse_id' => $warehouse_id, 'product_id' => $product_id, 'quantity' => $product_quantity, 'created_by' => \Auth::user()->id]
        );
    }

    public static function starting_number($id, $type)
    {

        if ($type == 'invoice') {
            $data = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->where('name', 'invoice_starting_number')->update(array('value' => $id));
        } elseif ($type == 'proposal') {
            $data = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->where('name', 'proposal_starting_number')->update(array('value' => $id));
        } elseif ($type == 'bill') {
            $data = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->where('name', 'bill_starting_number')->update(array('value' => $id));
        }

        return $data;
    }

    //  Start Storage Setting

    public static function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $settings = Utility::getStorageSetting();
            //                dd($settings);

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {

                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '20480000000';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }


                $file = $request->$key_name;

                if (count($custom_validation) > 0) {

                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }

                $validator = \Validator::make($request->all(), [
                    $key_name => $validation
                ]);

                if ($validator->fails()) {

                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];

                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {
                        //                    dd(\Storage::disk(),$path);
                        $request->$key_name->move(storage_path($path), $name);
                        $path = $path . $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                        // $path = $path.$name;
                        // dd($path);
                    }



                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {

            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    //only employee edit storage setting upload_coustom_file function

    public static function upload_coustom_file($request, $key_name, $name, $path, $data_key, $custom_validation = [])
    {

        try {
            $settings = Utility::getStorageSetting();


            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }


                $file = $request->$key_name;


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = \Validator::make($request->all(), [
                    $name => $validation
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {



                        \Storage::disk()->putFileAs(
                            $path,
                            $request->file($key_name)[$data_key],
                            $name
                        );


                        $path = $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $request->file($key_name)[$data_key],
                            $name
                        );

                        // $path = $path.$name;

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $request->file($key_name)[$data_key],
                            $name
                        );
                        // $path = $path.$name;
                        // dd($path);
                    }

                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function get_file($path)
    {
        $settings = Utility::settings();

        try {
            if ($settings['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                    ]
                );
            } elseif ($settings['storage_setting'] == 's3') {
                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
            }

            return \Storage::disk($settings['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }

    public static function getStorageSetting()
    {
        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1);
        $data     = $data->get();
        $settings = [
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",

        ];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    //  End Storage Setting

    private static $getRatingData = NULL;

    public static function getTargetrating($designationid, $competencyCount)
    {
        if (self::$getRatingData == null) {
            $indicator = Indicator::where('designation', $designationid)->first();

            if (!empty($indicator->rating) && ($competencyCount != 0)) {
                $rating = json_decode($indicator->rating, true);
                $starsum = array_sum($rating);

                $overallrating = $starsum / $competencyCount;
            } else {
                $overallrating = 0;
            }

            self::$getRatingData = $overallrating;
        }

        return self::$getRatingData;
    }

    //start Google Calendar
    public static function colorCodeData($type)
    {
        if ($type == 'event') {
            return 1;
        } elseif ($type == 'zoom_meeting') {
            return 2;
        } elseif ($type == 'task') {
            return 3;
        } elseif ($type == 'appointment') {
            return 11;
        } elseif ($type == 'rotas') {
            return 3;
        } elseif ($type == 'holiday') {
            return 4;
        } elseif ($type == 'call') {
            return 10;
        } elseif ($type == 'meeting') {
            return 5;
        } elseif ($type == 'leave') {
            return 6;
        } elseif ($type == 'work_order') {
            return 7;
        } elseif ($type == 'lead') {
            return 7;
        } elseif ($type == 'deal') {
            return 8;
        } elseif ($type == 'interview_schedule') {
            return 9;
        } else {
            return 11;
        }
    }

    public static $colorCode = [
        1 => 'event-warning',
        2 => 'event-secondary',
        3 => 'event-info',
        4 => 'event-warning',
        5 => 'event-danger',
        6 => 'event-dark',
        7 => 'event-black',
        8 => 'event-info',
        9 => 'event-dark',
        10 => 'event-success',
        11 => 'event-warning',

    ];

    public static function googleCalendarConfig()
    {
        $setting = Utility::settings();
        $path = storage_path($setting['google_calender_json_file']);
        config([
            'google-calendar.default_auth_profile' => 'service_account',
            'google-calendar.auth_profiles.service_account.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.token_json' => $path,
            'google-calendar.calendar_id' => isset($setting['google_clender_id']) ? $setting['google_clender_id'] : '',
            'google-calendar.user_to_impersonate' => '',


        ]);
    }

    public static function addCalendarData($request, $type)
    {
        Self::googleCalendarConfig();
        $event = new GoogleEvent();
        $event->name = $request->title;
        $event->startDateTime = Carbon::parse($request->start_date);
        $event->endDateTime = Carbon::parse($request->end_date);
        $event->colorId = Self::colorCodeData($type);
        $event->save();
    }

    public static function getCalendarData($type)
    {
        Self::googleCalendarConfig();
        $data = GoogleEvent::get();

        $type = Self::colorCodeData($type);
        $arrayJson = [];
        foreach ($data as $val) {
            $end_date = date_create($val->endDateTime);
            date_add($end_date, date_interval_create_from_date_string("1 days"));
            if ($val->colorId == "$type") {

                $arrayJson[] = [
                    "id" => $val->id,
                    "title" => $val->summary,
                    "start" => $val->startDateTime,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => Self::$colorCode[$type],
                    "allDay" => true,

                ];
            }
        }

        return $arrayJson;
    }

    //end Google Calendar


    //for pos reports
    public static function getStartEndMonthDates()
    {
        $first_day_of_current_month = Carbon::now()->startOfMonth()->subMonths(0)->toDateString();
        $first_day_of_next_month = Carbon::now()->startOfMonth()->subMonths(-1)->toDateString();

        return ['start_date' => $first_day_of_current_month, 'end_date' => $first_day_of_next_month];
    }

    public static function webhookSetting($module, $user_id = null)
    {
        if (!empty($user_id)) {
            $user = User::find($user_id);
        } else {
            $user = \Auth::user();
        }
        $webhook = WebhookSetting::where('module', $module)->where('created_by', '=', $user->id)->first();
        if (!empty($webhook)) {
            $url = $webhook->url;
            $method = $webhook->method;
            $reference_url  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $data['method'] = $method;
            $data['reference_url'] = $reference_url;
            $data['url'] = $url;
            return $data;
        }
        return false;
    }

    public static function WebhookCall($url = null, $parameter = null, $method = 'POST')
    {
        if (!empty($url) && !empty($parameter)) {
            try {

                $curlHandle = curl_init($url);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $parameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                $curlResponse = curl_exec($curlHandle);
                curl_close($curlHandle);
                if (empty($curlResponse)) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Throwable $th) {
                return false;
            }
        } else {
            return false;
        }
    }


    //start for cookie settings
    public static function getCookieSetting()
    {
        $data = \DB::table('settings')->whereIn('name', [
            'enable_cookie', 'cookie_logging', 'cookie_title',
            'cookie_description', 'necessary_cookies', 'strictly_cookie_title',
            'strictly_cookie_description', 'more_information_description', 'contactus_url'
        ])->get();
        $settings = [
            'enable_cookie' => 'off',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => '',
            'cookie_description' => '',
            'strictly_cookie_title' => '',
            'strictly_cookie_description' => '',
            'more_information_description' => '',
            'contactus_url' => '#',

        ];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    static function get_device_type($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if (preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            if (preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }
    //end for cookie settings


    // start for (plans) storage limit - for file upload size
    public static function updateStorageLimit($company_id, $image_size)
    {
        $image_size = number_format($image_size / 1048576, 2);

        $user   = User::find($company_id);
        $plan   = Plan::find($user->plan);
        $total_storage = $user->storage_limit + $image_size;
        if ($plan->storage_limit <= $total_storage && $plan->storage_limit != -1) {

            $error = __('Plan storage limit is over so please upgrade the plan.');
            return $error;
        } else {
            $user->storage_limit = $total_storage;
        }

        $user->save();
        return 1;
    }

    public static function changeStorageLimit($company_id, $file_path)
    {

        $files =  \File::glob(storage_path($file_path));
        $fileSize = 0;
        foreach ($files as $file) {
            $fileSize += \File::size($file);
        }

        $image_size = number_format($fileSize / 1048576, 2);
        $user   = User::find($company_id);
        $plan   = Plan::find($user->plan);
        $total_storage = $user->storage_limit - $image_size;
        $user->storage_limit = $total_storage;
        $user->save();

        $status = false;
        foreach ($files as $key => $file) {
            if (\File::exists($file)) {
                $status = \File::delete($file);
            }
        }


        return true;
    }
    // end for (plans) storage limit - for file upload size


    //for AI module
    public static function flagOfCountry()
    {
        $arr = [
            'ar' => '🇦🇪 ar',
            'zh' => '🇨🇳 zh',
            'da' => '🇩🇰 da',
            'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
            'he' => '🇮🇱 he',
            'it' =>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'nl' => '🇳🇱 nl',
            'pl' => '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇵🇹 pt-br',
        ];
        return $arr;
    }

    public static function langList()
    {
        $languages = [
            "ar" => "Arabic",
            "zh" => "Chinese",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "he" => "Hebrew",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish",
            "pt-br" => "Portuguese (Brazil)",
        ];
        return $languages;
    }

    public static function languagecreate()
    {
        $languages = Utility::langList();

        foreach ($languages as $key => $lang) {
            $languageExist = Language::where('code', $key)->first();
            if (empty($languageExist)) {
                $language = new Language();
                $language->code = $key;
                $language->full_name = $lang;
                $language->save();
            }
        }
    }

    public static function langSetting()
    {
        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1)->get();
        if (count($data) == 0) {
            $data = DB::table('settings')->where('created_by', '=', 1)->get();
        }
        $settings = [];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }


    public static function getChatGPTSettings()
    {
        $user = User::find(\Auth::user()->creatorId());
        $plan = \App\Models\Plan::find($user->plan);

        return $plan;
    }
    //start for chartOfAccount data show

    public static function getAccountBalance($account_id, $start_date = null, $end_date = null)
    {

        if (!empty($start_date) && !empty($end_date)) {
            $start = $start_date;
            $end   = $end_date;
        } else {
            $start = date('Y-01-01');
            $end   = date('Y-m-d', strtotime('+1 day'));
        }

        $invoice_product = ProductService::where('sale_chartaccount_id', $account_id)->get()->pluck('id');
        $invoiceData = InvoiceProduct::select(DB::raw('sum(price * quantity) as amount'));
        if (!empty($start_date) && !empty($end_date)) {
            $invoiceData->where('created_at', '>=', $start);
            $invoiceData->where('created_at', '<=', $end);
        }
        $invoiceData = $invoiceData->whereIn('product_id', $invoice_product)->first();
        $invoiceAmount = !empty($invoiceData->amount) ? $invoiceData->amount : 0;

        $getAccount = BankAccount::where('chart_account_id', $account_id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('id');

        $invoicePaymentAmount = InvoicePayment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $invoicePaymentAmount->where('date', '>=', $start);
            $invoicePaymentAmount->where('date', '<=', $end);
        }
        $invoicePaymentAmount = $invoicePaymentAmount->sum('amount');

        $revenueAmount = Revenue::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $revenueAmount->where('date', '>=', $start);
            $revenueAmount->where('date', '<=', $end);
        }

        $revenueAmount = $revenueAmount->sum('amount');

        $bill_product = ProductService::where('expense_chartaccount_id', $account_id)->get()->pluck('id');
        $billData = BillProduct::select(DB::raw('sum(price * quantity) as amount'));
        if (!empty($start_date) && !empty($end_date)) {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData = $billData->whereIn('product_id', $bill_product)->first();
        $billProductAmount = !empty($billData->amount) ? $billData->amount : 0;


        $billAmount = BillAccount::where('chart_account_id', $account_id);
        if (!empty($start_date) && !empty($end_date)) {
            $billAmount->where('created_at', '>=', $start);
            $billAmount->where('created_at', '<=', $end);
        }
        $billAmount = $billAmount->sum('price');


        $billPaymentAmount = BillPayment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $billPaymentAmount->where('date', '>=', $start);
            $billPaymentAmount->where('date', '<=', $end);
        }
        $billPaymentAmount = $billPaymentAmount->sum('amount');


        $paymentAmount = Payment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $paymentAmount->where('date', '>=', $start);
            $paymentAmount->where('date', '<=', $end);
        }
        $paymentAmount = $paymentAmount->sum('amount');

        $journalCredit = JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
            ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')->where('journal_entries.created_by', '=', \Auth::user()->creatorId())->where('account', $account_id);
        $journalCredit->where('journal_items.created_at', '>=', $start);
        $journalCredit->where('journal_items.created_at', '<=', $end);
        $journalCredit = $journalCredit->sum('credit');

        $journalDebit = JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
            ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')->where('journal_entries.created_by', '=', \Auth::user()->creatorId())->where('account', $account_id);
        $journalDebit->where('journal_items.created_at', '>=', $start);
        $journalDebit->where('journal_items.created_at', '<=', $end);
        $journalDebit = $journalDebit->sum('debit');

        $balance   =  ($invoiceAmount + $invoicePaymentAmount + $revenueAmount  + $journalCredit) - ($journalDebit + $billProductAmount + $billAmount + $billPaymentAmount + $paymentAmount);

        return $balance;
    }

    public static function getAccountData($account_id, $start_date = null, $end_date = null)
    {

        // $account_id = 50;
        if (!empty($start_date) && !empty($end_date)) {
            $start = $start_date;
            $end   = $end_date;
        } else {
            $start = date('Y-01-01');
            $end   = date('Y-m-d', strtotime('+1 day'));
        }

        //For Invoice Product Create
        $invoice_product = ProductService::where('sale_chartaccount_id', $account_id)->get()->pluck('id');
        $invoice = InvoiceProduct::whereIn('product_id', $invoice_product);
        if (!empty($start_date) && !empty($end_date)) {
            $invoice->where('created_at', '>=', $start);
            $invoice->where('created_at', '<=', $end);
        }
        $invoice = $invoice->get();


        $getAccount = BankAccount::where('chart_account_id', $account_id)->get()->pluck('id');
        //For Invoice Payment
        $invoicePayment = InvoicePayment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $invoicePayment->where('date', '>=', $start);
            $invoicePayment->where('date', '<=', $end);
        }
        $invoicePayment = $invoicePayment->get();

        //For Revenue
        $revenue = Revenue::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $revenue->where('date', '>=', $start);
            $revenue->where('date', '<=', $end);
        }
        $revenue = $revenue->get();

        //For Bill

        $bill_product = ProductService::where('expense_chartaccount_id', $account_id)->get()->pluck('id');
        $bill = BillProduct::whereIn('product_id', $bill_product);
        if (!empty($start_date) && !empty($end_date)) {
            $bill->where('created_at', '>=', $start);
            $bill->where('created_at', '<=', $end);
        }
        $bill = $bill->get();

        $billData = BillAccount::where('chart_account_id', $account_id);
        if (!empty($start_date) && !empty($end_date)) {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData = $billData->get();

        $billPayment = BillPayment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $billPayment->where('date', '>=', $start);
            $billPayment->where('date', '<=', $end);
        }
        $billPayment = $billPayment->get();


        $payment = Payment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $payment->where('date', '>=', $start);
            $payment->where('date', '<=', $end);
        }
        $payment = $payment->get();


        $journalItems = JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
            ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')->where('journal_entries.created_by', '=', \Auth::user()->creatorId())->where('account', $account_id);
        $journalItems->where('journal_items.created_at', '>=', $start);
        $journalItems->where('journal_items.created_at', '<=', $end);
        $journalItems = $journalItems->get();

        $data = [];
        $data['invoice'] = $invoice;
        $data['invoicepayment'] = $invoicePayment;
        $data['revenue'] = $revenue;
        $data['bill'] = $bill;
        $data['billdata'] = $billData;
        $data['billpayment'] = $billPayment;
        $data['payment'] = $payment;
        $data['journalItem'] = $journalItems;

        return $data;
    }

    //end for chartOfAccount data show

    //export balance sheet report

    public static function getBalanceSheetCredit($account_id, $start_date = null, $end_date = null)
    {

        if (!empty($start_date) && !empty($end_date)) {
            $start = $start_date;
            $end   = $end_date;
        } else {
            $start = date('Y-m-01');
            $end   = date('Y-m-t');
        }

        $invoice_product = ProductService::where('sale_chartaccount_id', $account_id)->get()->pluck('id');
        $invoiceData = InvoiceProduct::select(DB::raw('sum(price * quantity) as amount'));

        if (!empty($start_date) && !empty($end_date)) {
            $invoiceData->where('created_at', '>=', $start);
            $invoiceData->where('created_at', '<=', $end);
        }
        $invoiceData = $invoiceData->whereIn('product_id', $invoice_product)->first();
        $invoiceAmount = !empty($invoiceData->amount) ? $invoiceData->amount : 0;
        $getAccount = BankAccount::where('chart_account_id', $account_id)->get()->pluck('id');

        $invoicePaymentAmount = InvoicePayment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $invoicePaymentAmount->where('date', '>=', $start);
            $invoicePaymentAmount->where('date', '<=', $end);
        }
        $invoicePaymentAmount = $invoicePaymentAmount->sum('amount');


        $revenueAmount = Revenue::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $revenueAmount->where('date', '>=', $start);
            $revenueAmount->where('date', '<=', $end);
        }
        $revenueAmount = $revenueAmount->sum('amount');


        $balance   =  ($invoiceAmount + $invoicePaymentAmount + $revenueAmount);
        return $balance;
    }

    public static function getBalanceSheetDebit($account_id, $start_date = null, $end_date = null)
    {

        if (!empty($start_date) && !empty($end_date)) {
            $start = $start_date;
            $end   = $end_date;
        } else {
            $start = date('Y-m-01');
            $end   = date('Y-m-t');
        }

        $bill_product = ProductService::where('expense_chartaccount_id', $account_id)->get()->pluck('id');
        $billData = BillProduct::select(DB::raw('sum(price * quantity) as amount'));
        if (!empty($start_date) && !empty($end_date)) {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData = $billData->whereIn('product_id', $bill_product)->first();
        $billProductAmount = !empty($billData->amount) ? $billData->amount : 0;


        $billAmount = BillAccount::where('chart_account_id', $account_id);
        if (!empty($start_date) && !empty($end_date)) {
            $billAmount->where('created_at', '>=', $start);
            $billAmount->where('created_at', '<=', $end);
        }
        $billAmount = $billAmount->sum('price');

        $getAccount = BankAccount::where('chart_account_id', $account_id)->get()->pluck('id');


        $billPaymentAmount = BillPayment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $billPaymentAmount->where('date', '>=', $start);
            $billPaymentAmount->where('date', '<=', $end);
        }
        $billPaymentAmount = $billPaymentAmount->sum('amount');


        $paymentAmount = Payment::whereIn('account_id', $getAccount);
        if (!empty($start_date) && !empty($end_date)) {
            $paymentAmount->where('date', '>=', $start);
            $paymentAmount->where('date', '<=', $end);
        }
        $paymentAmount = $paymentAmount->sum('amount');

        $balance   =  ($billProductAmount + $billAmount + $billPaymentAmount + $paymentAmount);
        return $balance;
    }
    //end export balance sheet report

    //trial balance sheet report

    public static function trialBalance($account_id, $start, $end)
    {
        $journalItem = JournalItem::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(debit) as totalDebit'), \DB::raw('sum(credit) as totalCredit'));
        $journalItem->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal');
        $journalItem->leftjoin('chart_of_accounts', 'journal_items.account', 'chart_of_accounts.id');
        $journalItem->where('chart_of_accounts.type', $account_id);
        $journalItem->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $journalItem->where('journal_items.created_at', '>=', $start);
        $journalItem->where('journal_items.created_at', '<=', $end);
        $journalItem->groupBy('account');
        $journalItem = $journalItem->get()->toArray();


        $invoice = InvoiceProduct::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('0 as totalDebit'), \DB::raw('sum(price*invoice_products.quantity) as totalCredit'));
        $invoice->leftjoin('product_services', 'product_services.id', 'invoice_products.product_id');
        $invoice->leftjoin('chart_of_accounts', 'product_services.sale_chartaccount_id', 'chart_of_accounts.id');
        $invoice->where('chart_of_accounts.type', $account_id);
        $invoice->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $invoice->where('invoice_products.created_at', '>=', $start);
        $invoice->where('invoice_products.created_at', '<=', $end);
        $invoice->groupBy('product_services.sale_chartaccount_id');
        $invoice = $invoice->get()->toArray();

        $invoicePayment = InvoicePayment::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'));
        $invoicePayment->leftjoin('bank_accounts', 'bank_accounts.id', 'invoice_payments.account_id');
        $invoicePayment->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $invoicePayment->where('chart_of_accounts.type', $account_id);
        $invoicePayment->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $invoicePayment->where('invoice_payments.created_at', '>=', $start);
        $invoicePayment->where('invoice_payments.created_at', '<=', $end);
        $invoicePayment->groupBy('account_id');
        $invoicePayment = $invoicePayment->get()->toArray();

        $revenue = Revenue::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('0 as totalDebit'), \DB::raw('sum(amount) as totalCredit'));
        $revenue->leftjoin('bank_accounts', 'bank_accounts.id', 'revenues.account_id');
        $revenue->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $revenue->where('chart_of_accounts.type', $account_id);
        $revenue->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $revenue->where('revenues.created_at', '>=', $start);
        $revenue->where('revenues.created_at', '<=', $end);
        $revenue->groupBy('chart_account_id');
        $revenue = $revenue->get()->toArray();

        $bill = BillProduct::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(price*bill_products.quantity) as totalDebit'), \DB::raw('0 as totalCredit'));
        $bill->leftjoin('product_services', 'product_services.id', 'bill_products.product_id');
        $bill->leftjoin('chart_of_accounts', 'product_services.expense_chartaccount_id', 'chart_of_accounts.id');
        $bill->where('chart_of_accounts.type', $account_id);
        $bill->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $bill->where('bill_products.created_at', '>=', $start);
        $bill->where('bill_products.created_at', '<=', $end);
        $bill->groupBy('product_services.expense_chartaccount_id');
        $bill = $bill->get()->toArray();

        $billAccount = BillAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(price) as totalDebit'), \DB::raw('0 as totalCredit'));
        $billAccount->leftjoin('chart_of_accounts', 'bill_accounts.chart_account_id', 'chart_of_accounts.id');
        $billAccount->where('chart_of_accounts.type', $account_id);
        $billAccount->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $billAccount->where('bill_accounts.created_at', '>=', $start);
        $billAccount->where('bill_accounts.created_at', '<=', $end);
        $billAccount->groupBy('chart_account_id');
        $billAccount = $billAccount->get()->toArray();

        $billPayment = BillPayment::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'));
        $billPayment->leftjoin('bank_accounts', 'bank_accounts.id', 'bill_payments.account_id');
        $billPayment->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $billPayment->where('chart_of_accounts.type', $account_id);
        $billPayment->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $billPayment->where('bill_payments.created_at', '>=', $start);
        $billPayment->where('bill_payments.created_at', '<=', $end);
        $billPayment->groupBy('account_id');
        $billPayment = $billPayment->get()->toArray();

        $payments = Payment::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'));
        $payments->leftjoin('bank_accounts', 'bank_accounts.id', 'payments.account_id');
        $payments->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $payments->where('chart_of_accounts.type', $account_id);
        $payments->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $payments->where('payments.created_at', '>=', $start);
        $payments->where('payments.created_at', '<=', $end);
        $payments->groupBy('account_id');
        $payments = $payments->get()->toArray();

        // if($billAccount != [])
        // {
        //     for ($i = 0; $i < count($invoicePayment); $i++) {
        //         $invoicePayment[$i]["totalDebit"] = (
        //             ($invoicePayment[$i]["totalDebit"]) - ($billAccount[$i]["totalDebit"])
        //         );
        //     }
        // }

        if ($billPayment != []) {
            for ($i = 0; $i < count($invoicePayment); $i++) {
                $invoicePayment[$i]["totalDebit"] = (
                    ($invoicePayment[$i]["totalDebit"]) - ($billPayment[$i]["totalDebit"])
                );
            }
        }

        $total = array_merge($invoice, $journalItem, $revenue, $bill, $billAccount, $payments, $invoicePayment);
        return $total;
    }
    //end trial balance sheet report


    public static function smtpDetail($user_id)
    {
        $settings = self::settingsById($user_id);
        $smtpDetail = config(
            [
                'mail.driver'       => $settings['mail_driver'],
                'mail.host'         => $settings['mail_host'],
                'mail.port'         => $settings['mail_port'],
                'mail.encryption'   => $settings['mail_encryption'],
                'mail.username'     => $settings['mail_username'],
                'mail.password'     => $settings['mail_password'],
                'mail.from.address' => $settings['mail_from_address'],
                'mail.from.name'    => $settings['mail_from_name'],
            ]
        );

        return $smtpDetail;
    }

    public static function getPusherSetting()
    {
        $settings = Utility::settingsById(1);
        if ($settings) {
            config([
                'chatify.pusher.key' => isset($settings['pusher_app_key']) ? $settings['pusher_app_key'] : '',
                'chatify.pusher.secret' => isset($settings['pusher_app_secret']) ? $settings['pusher_app_secret'] : '',
                'chatify.pusher.app_id' => isset($settings['pusher_app_id']) ? $settings['pusher_app_id'] : '',
                'chatify.pusher.options.cluster' => isset($settings['pusher_app_cluster']) ? $settings['pusher_app_cluster'] : '',
            ]);
            return $settings;
        }
    }
}
