<?php

namespace App\Http\Controllers\MoneyTransfer\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Setting\Setting;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class SettingsController extends Controller
{

    public function index()
    {
        // $settings=Setting::all();
        // $data = array();
        // foreach($settings as $key => $value){
        //     // $data[] = array(
        //     //     $value->key => $value->value,
        //     // );
        //     define($value->key, $value->value);
        // }
        // dd($data);
        $data = array(
            'config_store_id' => config_store_id,
            'config_language_id' => config_language_id,
            'config_language' => config_language,
            'config_account_id' => config_account_id,
            'config_icon' => config_icon,
            'config_image' => config_image,
            'config_logo' => config_logo,
            'config_name' => config_name,
            'config_contact' => config_contact,
            'config_email' => config_email,
            'config_fax' => config_fax,
            'config_owner' => config_owner,
            'config_address' => config_address,
            'config_transfer_prefix' => config_transfer_prefix,
            'config_invoice_prefix' => config_invoice_prefix,
            'config_receipt_prefix' => config_receipt_prefix,
            'config_url' => config_url,
            'config_checkout_id' => config_checkout_id,
            'config_currency' => config_currency,
            'config_checkout_guest' => config_checkout_guest,
            'config_cart_weight' => config_cart_weight,
            'config_customer_price' => config_customer_price,
            'config_customer_group_id' => config_customer_group_id,
            'config_tax_customer' => config_tax_customer,
            'config_tax_default' => config_tax_default,
            'config_currency_code' => config_currency_code,
            'config_mobile_delivery_method' => config_mobile_delivery_method,
            'config_order_status_id' => config_order_status_id,
            'config_secure' => config_secure,
            'config_ssl' => config_ssl,
            'config_stock_checkout' => config_stock_checkout,
            'config_stock_display' => config_stock_display,
            'config_tax' => config_tax,
            'config_tax_customer' => config_tax_customer,
            'config_tax_default' => config_tax_default,
            'config_zone_id' => config_zone_id,
            'config_country_id' => config_country_id,
            'config_comment' => config_comment,
            'config_open' => config_open,
            'config_geocode' => config_geocode,
            'config_layout_id' => config_layout_id,
            'config_theme' => config_theme,
            'config_meta_keyword' => config_meta_keyword,
            'config_meta_description' => config_meta_description,
            'config_meta_title' => config_meta_title,
            'config_review_status' => config_review_status,
            'config_user_group_regsiter' => config_user_group_regsiter
        );
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }

    public function show($id){
        return response()->json(Setting::find($id));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        // DB::table('setting')->delete();
        $data=(new Setting)->getFillable();
        $data=$request->only($data);
        $new_data = array();
        $code = "config";
		foreach ($input as $key => $value) {
            // $key['config_image'] = "here is image";
			if (substr($key, 0, strlen($code)) == $code) {

				if (!is_array($value)) {
                    Setting::where('code',$code)->where('key',$key)->update([
                        'store_id' => 0,
                        'code' => $code,
                        'key' => $key,
                        'value' => $value
                    ]);

				} else {
                    Setting::where('code',$code)->where('key',$key)->update([
                        'store_id' => 0,
                        'code' => $code,
                        'key' => $key,
                        'value' => json_encode($value, true),
                        'serialized' => '1'
                    ]);

				}

			}

		}
            
        return response()->json(['success'=>true,'message'=>'Setting has been save successfully.','data'=>$input,'total'=>count($input)]);
    }

    public function edit($id)
    {
        return (new DataAction)->EditData(Setting::class,$id);
        
    }

    public function update(Request $request,$id)
    {
        
        $data=$request['data'];

        return (new DataAction)->UpdateData(Setting::class,$data,'setting_id',$id);

    }

    public function destroy($id)
    {

        return (new DataAction)->DeleteData(Setting::class,'setting_id',$id);
        
    }

    public function item()
    {

        $settingItems=Setting::FetchSettingItem();

        return response()->json($settingItems);

    }
    

}
