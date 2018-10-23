<?php

namespace App\Http\Controllers\MoneyTransfer\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Currency\Currency;
use App\Http\Models\MoneyTransfer\Booking\Booking;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use App\Http\Models\MoneyTransfer\Catalog\Product;
use App\Http\Models\MoneyTransfer\Order\Order;
use App\Http\Models\MoneyTransfer\Order\OrderProduct;
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
use App\Http\Models\MoneyTransfer\User\User;
use App\Helpers\common;

class BookingController extends Controller
{
    public function index()
    {
        $Bookings = Booking::all();
        // dd($Bookings);
        $booking = [];
        foreach($Bookings as $Booking){
            $booking[] = array(
                'booking_id' => $Booking->booking_id,
                // 'account_no' => $Booking->Accounts->account_no,
                // 'merchant_name' => AccountMaster::getMerchant($Booking->Accounts->account_master_id,"merchant_name"),
                'account_id' => $Booking->account_id,
                'account_master_id' => $Booking->account_master_id,
                'merchant_name' => AccountMaster::getMerchant($Booking->account_master_id,"merchant_name"),
                'merchant_id' => AccountMaster::getMerchant($Booking->account_master_id,"merchant_id"),
                'quantity' => $Booking->quantity,
                'booking_type' => $Booking->booking_type,
                'product_id' => $Booking->product_id,
                'category_id' => $Booking->category_id,
                'origin' => $Booking->origin,
                'destination' => $Booking->destination,
                'sector' => $Booking->sector,
                'booking_date' => $Booking->booking_date,
                'fly_date' => $Booking->fly_date,
                'return_date' => $Booking->return_date,
                'is_approved' => $Booking->is_approved,
                'exchange_rate' => $Booking->exchange_rate,
                'charge_fee' => $Booking->charge_fee,
                'price' => $Booking->price,
                'currency' => $Booking->currency,
                'tax_amount' => $Booking->tax_amount,
                'total_price' => $Booking->total_price,
                'status' => $Booking->status,
            );
        }
        return response()->json(["success"=>true,"message"=>"Succcess","data"=>$booking,"count"=>count($booking)]);
    }

    public function getCurrency(){
        return Currency::select('currency_id as value','code as text')->get();
    }

    public function store(Request $request)
    {
        // return (new DataAction)->StoreData(Booking::class,$condition,"",$data);
        // //return response()->json($data);
        $input = $request->all();
        // $input['booking_date'] = date("Y-m-d H:i:s");
        // $input['sector'] = $input['origin'].' - '.$input['destination'];
        // dd($input);
        $Product = Product::Where('product_id',$input['product_id'])->first();
        if($Product && $Product->quantity>=$request->quantity){
            $account_no = Account::fetchAccountById($input['account_id'],'account_no');
            if(Account::checkHasAccount(Account::fetchAccount($account_no,'account_id'))){
                // $account_no = Account::fetchAccountById($data['account_id'],'account_no');
                if(common::calculateSaveAccount($account_no,$Product->price)){
                    if(Account::checkTransactionAccount(Account::fetchAccount($account_no,'account_id'),$Product->price)){
                        $account_master_id = Account::fetchAccount($account_no,'account_master_id');
                        $data=(new Booking)->getFillable();
                        $data=$request->only($data);
                        $data['price'] = common::getAmountConvertCurrency($Product->price,Account::getCurrencyByAccountId(Account::fetchAccount($account_no,'account_id'),'currency_id'),config_currency)*floatval($input['quantity']);
                        // $data['amount_top_up'] = $Product->price;
                        $data['currency'] = Account::getCurrencyByAccountNo($account_no,'code');
                        $data['date_added'] = date("Y-m-d H:i:s");
                        $data['sector'] = $data['origin'].' - '.$data['destination'];
                        $data['booking_date'] = date("Y-m-d H:i:s");
                        $data['date_modified'] = date("Y-m-d H:i:s");
                        $condition=[
                            //'key'=>$data['key']
                        ];
                        $message = "Do Bookings";
                        $transaction_type = 'Do Bookings';
                        $sending_amount = 0;
                        common::DoTransactionByAccount($account_master_id,$account_no,$sending_amount,$message,$transaction_type);
                        return (new DataAction)->StoreData(Booking::class,$condition,'',$data);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Booking failed, You reached to maximum transaction rule!";
                        return response()->json($data); 
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Booking failed, Insuficient balance!";
                    return response()->json($data);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to Booking!";
                return response()->json($data); 
            }
        }else{
            $data['success'] = false;
            $data['message'] = "Quantity you ordered may be out of stock, please check stock product again!";
            return response()->json($data); 
        }
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Booking::class,$id);
    }
    public function update(Request $request,$id)
    {
        //  $data=(new Booking)->getFillable();
        //  $data=$request->only($data);
        //  if($data['is_approved']){
        //     $data['status'] = 1;
        //  }
        //  return (new DataAction)->UpdateData(Booking::class,$data,'Booking_id',$id);
        $input = $request->all();
        $Product = Product::Where('product_id',$input['product_id'])->first();
        if($Product && $Product->quantity>=$request->quantity){
            $account_no = Account::fetchAccountById($input['account_id'],'account_no');
            if(Account::checkHasAccount(Account::fetchAccount($account_no,'account_id'))){
                // $account_no = Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($account_no,$Product->price)){
                    if(Account::checkTransactionAccount(Account::fetchAccount($account_no,'account_id'),$Product->price)){
                        $account_master_id = Account::fetchAccount($account_no,'account_master_id');
                        $data=(new Booking)->getFillable();
                        $data=$request->only($data);
                        $total_amount = common::getAmountConvertCurrency($Product->price,Account::getCurrencyByAccountId(Account::fetchAccount($account_no,'account_id'),'currency_id'),config_currency)*floatval($input['quantity']);
                        $data['price'] = $total_amount;
                        $data['currency'] = Account::getCurrencyByAccountNo($account_no,'code');
                        $data['date_modified'] = date("Y-m-d H:i:s");
                        $condition=[
                            //'key'=>$data['key']
                        ];
                        $message = "Do Bookings";
                        $transaction_type = 'Do Bookings';
                        $sending_amount = 0;
                        if(isset($input['is_approved']) && $input['is_approved']==1){
                            $data['status']=1;
                            $sending_amount = $total_amount;
                            $getUserId = AccountMaster::getMerchant($account_master_id,'user_id');
                            $User = User::Where('id',$getUserId)->first();
                            $OrderLastId = Order::insertGetId([
                                'invoice_no' => config_invoice_prefix.rand(11111111, 99999999),
                                'account_id' => Account::fetchAccount($account_no,'account_id'),
                                'firstname' => $User->first_name,
                                'lastname' => $User->last_name,
                                'email' => $User->email,
                                'telephone' => $User->telephone_1,
                                'payment_firstname' => $User->first_name,
                                'payment_lastname' => $User->last_name,
                                'payment_company' => $User->company,
                                'payment_address_1' => $User->address_1,
                                'payment_address_2' => $User->address_2,
                                'payment_city' => $User->city,
                                'payment_postcode' => $User->code,
                                'payment_country' => $User->country,
                                'payment_country_id' => $User->country_id,
                                'payment_method' => 'Credit Card',
                                'comment' => 'Booking',
                                'total' => $Product->price,
                                'order_status_id' => 1,
                                'commission' => 0,
                                'currency_id' => Account::getCurrencyByAccountNo($account_no,'currency_id'),
                                'currency_code' => Account::getCurrencyByAccountNo($account_no,'code'),
                                'currency_value' => Account::getCurrencyByAccountNo($account_no,'value'),
                                'exchange_rate' => Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountNo($account_no,'currency_id')),
                                'updated_add' => date('Y-m-d')
                            ]);

                            if($OrderLastId){ 
                                $createOrderProduct = OrderProduct::create([
                                    "order_id" => $OrderLastId,
                                    "product_id" => $Product->product_id,
                                    "name" => $Product->name,
                                    "model" =>$Product->model,
                                    "quantity" => $Product->quantity,
                                    "price" => $Product->price,
                                    "total" => floatval($Product->price)+config_tax,
                                    "tax" => config_tax
                                ]);
                                $product_quantity = $Product->quantity;
                                $remain_qty_request = floatval($product_quantity)-floatval($request->quantity);
                                $calProductQty = Product::Where('product_id',$Product->product_id)->update(['quantity'=>$remain_qty_request]);
                                if(!$calProductQty){
                                    Order::where('order_id',$OrderLastId)->delete();    
                                }
                            }else{
                                Order::where('order_id',$OrderLastId)->delete();
                                $data['success'] = false;
                                $data['message'] = "Booking failed, You reached to maximum transaction rule!";
                                return response()->json($data);
                            }
                        }
                        
                        common::DoTransactionByAccount($account_master_id,$account_no,$sending_amount,$message,$transaction_type);
                        return (new DataAction)->UpdateData(Booking::class,$data,'booking_id',$id);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Booking failed, You reached to maximum transaction rule!";
                        return response()->json($data); 
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Booking failed, Insuficient balance!";
                    return response()->json($data);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to Booking!";
                return response()->json($data); 
            }
        }else{
            $data['success'] = false;
            $data['message'] = "Quantity you ordered may be out of stock, please check stock product again!";
            return response()->json($data); 
        }
    }
    public function destroy($id)
    {
        return (new DataAction)->DeleteData(Booking::class,'Booking_id',$id);
    }
}
