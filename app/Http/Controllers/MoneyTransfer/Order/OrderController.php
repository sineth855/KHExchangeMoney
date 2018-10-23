<?php

namespace App\Http\Controllers\MoneyTransfer\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Order\Order;
use App\Http\Models\MoneyTransfer\Order\OrderProduct;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
/*
    this controller use for create any validation function
    currently it have one function to validate data if exist or not yet exist
    then return the json to pass to axios.get() in veujs
    this function there are 3 parameter(tablename,fieldname,value)
        - tablename: table that we want to check
        - fieldname: field of that table we want to filter
        - value: value of field we want to check
*/
use App\Http\Controllers\MoneyTransfer\commons\ValidateDataController;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class OrderController extends Controller
{
    public function index()
    {
        $Orders = Order::OrderBy('order_id','desc')->get();
        $data = array();
        foreach($Orders as $Order){
            $order_id = $Order->order_id;
            $OrderProducts = OrderProduct::where('order_id',$order_id)->get();
            $data_order_product = array();
            foreach($OrderProducts as $OrderProduct){
                $data_order_product[] = array(
                    'product_id' => $OrderProduct->product_id,
                    'name' => $OrderProduct->name,
                    'model' => $OrderProduct->model,
                    'quantity' => $OrderProduct->quantity,
                    'price' => $OrderProduct->price,
                    'total' => $OrderProduct->total,
                    'tax' => $OrderProduct->tax,
                );
            }
            $data[] = array(
                'order_id' => $Order->order_id,
                'invoice_no' => $Order->invoice_no,
                'account_id' => $Order->account_id,
                'account_no' => Account::fetchAccountById($Order->account_id,'account_no'),
                'firstname' => $Order->firstname,
                'lastname' => $Order->lastname,
                'email' => $Order->email,
                'telephone' => $Order->telephone,
                'custom_field' => $Order->custom_field,
                'payment_firstname' => $Order->payment_firstname,
                'payment_lastname' => $Order->payment_lastname,
                'payment_company' => $Order->payment_company,
                'payment_address_1' => $Order->payment_address_1,
                'payment_address_2' => $Order->payment_address_2,
                'payment_city' => $Order->payment_city,
                'payment_postcode' => $Order->payment_postcode,
                'payment_country' => $Order->payment_country,
                'payment_country_id' => $Order->payment_country_id,
                'payment_zone' => $Order->payment_zone,
                'payment_zone_id' => $Order->payment_zone_id,
                'payment_address_format' => $Order->payment_address_format,
                'payment_custom_field' => $Order->payment_custom_field,
                'payment_method' => $Order->payment_method,
                'payment_code' => $Order->payment_code,
                'comment' => $Order->comment,
                'total' => $Order->total,
                'order_status_id' => $Order->order_status_id,
                'commission' => $Order->commission,
                'currency_id' => $Order->currency_id,
                'currency_code' => $Order->currency_code,
                'currency_value' => $Order->currency_value,
                'exchange_rate' => $Order->exchange_rate,
                'forwarded_ip' => $Order->forwarded_ip,
                'user_agent' => $Order->user_agent,
                'accept_language' => $Order->accept_language,
                'created_add' => $Order->created_add,
                'updated_add' => $Order->updated_add,
                'data_order_product' => $data_order_product
            );
        }
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $data=$request['data'];
        $data["name"]=$input['name'];

        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(Order::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Order::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new Order)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(Order::class,$data,'order_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(Order::class,'order_id',$id);
    }
}
