<?php

namespace App\Http\Controllers\MoneyTransfer\Voucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Voucher\BuyVoip;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use App\Http\Models\MoneyTransfer\Catalog\Product;
use App\Http\Models\MoneyTransfer\Order\Order;
use App\Http\Models\MoneyTransfer\Order\OrderProduct;
use App\Http\Models\MoneyTransfer\User\User;
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
use App\Helpers\common;

class BuyVoipController extends Controller
{
    public function index()
    {
        $BuyVoip = BuyVoip::OrderBy('buy_voip_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$BuyVoip,'total'=>count($BuyVoip)]);
    }
    public function store(Request $request)
    {   
        $input = $request->all();
        // dd($input);
        $Product = Product::Where('product_id',$input['product_id'])->first();
        if($Product && $Product->quantity>=$request->quantity){
            if(Account::checkHasAccount(Account::fetchAccount($input['account_no'],'account_id'))){
                // $account_no = Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($input['account_no'],$Product->price)){
                    if(Account::checkTransactionAccount(Account::fetchAccount($input['account_no'],'account_id'),$Product->price)){
                        $account_master_id = Account::fetchAccount($input['account_no'],'account_master_id');
                        $data=(new BuyVoip)->getFillable();
                        $data=$request->only($data);
                        $data['amount'] = common::getAmountConvertCurrency($Product->price,Account::getCurrencyByAccountId(Account::fetchAccount($input['account_no'],'account_id'),'currency_id'),config_currency)*floatval($input['quantity']);
                        // $data['amount_top_up'] = $Product->price;
                        $data['currency'] = Account::getCurrencyByAccountNo($input['account_no'],'code');
                        $data['date_added'] = date("Y-m-d");
                        $data['date_modified'] = date("Y-m-d");
                        $condition=[
                            //'key'=>$data['key']
                        ];
                        $message = "Purchase Voip";
                        $transaction_type = 'Purchase Voip';
                        $sending_amount = 0;
                        common::DoTransactionByAccount($account_master_id,$input['account_no'],$sending_amount,$message,$transaction_type);
                        return (new DataAction)->StoreData(BuyVoip::class,$condition,'',$data);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Buy voip failed, You reached to maximum transaction rule!";
                        return response()->json($data); 
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Buy voip failed, Insuficient balance!";
                    return response()->json($data);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to Buy voip!";
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
        $BuyVoip = BuyVoip::where("buy_voip_id",$id)->first();
        $Account = Account::where('account_no',$BuyVoip->account_no)->first();
        $data = array(
            'category_id' => $BuyVoip->category_id,
            'product_id' => $BuyVoip->product_id,
            'quantity' => $BuyVoip->quantity,
            'account_master_id' => $Account->account_master_id,
            'account_no' => $BuyVoip->account_no,
            'voip_account_no' => $BuyVoip->voip_account_no,
            'currency' => $BuyVoip->currency,
            'country_id' => $BuyVoip->country_id,
            'date_added' => $BuyVoip->transfer_date,
            'date_added' => $BuyVoip->transfer_date,
            'status' => $BuyVoip->status
        );
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($BuyVoip)]);
        // return (new DataAction)->EditData(BuyVoip::class,$id);
    }
    public function edit($id)
    {
        $BuyVoip = BuyVoip::where("buy_voip_id",$id)->first();
        $Account = Account::where('account_no',$BuyVoip->account_no)->first();
        $data = array(
            'category_id' => $BuyVoip->category_id,
            'product_id' => $BuyVoip->product_id,
            'quantity' => $BuyVoip->quantity,
            'account_master_id' => $Account->account_master_id,
            'account_no' => $BuyVoip->account_no,
            'voip_account_no' => $BuyVoip->voip_account_no,
            'currency' => $BuyVoip->currency,
            'country_id' => $BuyVoip->country_id,
            'date_added' => $BuyVoip->transfer_date,
            'date_added' => $BuyVoip->transfer_date,
            'status' => $BuyVoip->status
        );
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($BuyVoip)]);
    }
    public function update(Request $request,$id)
    {
        // $data=(new BuyVoip)->getFillable();
        // $data=$request->only($data);
        // // if(@$data['image']){
        // //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // // }
        $input = $request->all();
        $Product = Product::Where('product_id',$input['product_id'])->first();
        if($Product && $Product->quantity>=$request->quantity){
            if(Account::checkHasAccount(Account::fetchAccount($input['account_no'],'account_id'))){
                // $account_no = Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($input['account_no'],$Product->price)){
                    if(Account::checkTransactionAccount(Account::fetchAccount($input['account_no'],'account_id'),$Product->price)){
                        $account_master_id = Account::fetchAccount($input['account_no'],'account_master_id');
                        $data=(new BuyVoip)->getFillable();
                        $data=$request->only($data);
                        $total_amount = common::getAmountConvertCurrency($Product->price,Account::getCurrencyByAccountId(Account::fetchAccount($input['account_no'],'account_id'),'currency_id'),config_currency)*floatval($input['quantity']);
                        $data['amount'] = $total_amount;
                        $data['currency'] = Account::getCurrencyByAccountNo($input['account_no'],'code');
                        $data['date_modified'] = date("Y-m-d");
                        $condition=[
                            //'key'=>$data['key']
                        ];
                        $message = "Purchase Voip";
                        $transaction_type = 'Purchase Voip';
                        $sending_amount = 0;
                        if(isset($input['status']) && $input['status']==1){
                            $sending_amount = $total_amount;
                            $getUserId = AccountMaster::getMerchant($account_master_id,'user_id');
                            $User = User::Where('id',$getUserId)->first();
                            $OrderLastId = Order::insertGetId([
                                'invoice_no' => config_invoice_prefix.rand(11111111, 99999999),
                                'account_id' => Account::fetchAccount($input['account_no'],'account_id'),
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
                                'comment' => 'Buy voip',
                                'total' => $Product->price,
                                'order_status_id' => 1,
                                'commission' => 0,
                                'currency_id' => Account::getCurrencyByAccountNo($input['account_no'],'currency_id'),
                                'currency_code' => Account::getCurrencyByAccountNo($input['account_no'],'code'),
                                'currency_value' => Account::getCurrencyByAccountNo($input['account_no'],'value'),
                                'exchange_rate' => Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountNo($input['account_no'],'currency_id')),
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
                                $data['message'] = "Buy voip failed, You reached to maximum transaction rule!";
                                return response()->json($data);
                            }
                        }
                        
                        common::DoTransactionByAccount($account_master_id,$input['account_no'],$sending_amount,$message,$transaction_type);
                        return (new DataAction)->UpdateData(BuyVoip::class,$data,'buy_voip_id',$id);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Buy voip failed, You reached to maximum transaction rule!";
                        return response()->json($data); 
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Buy voip failed, Insuficient balance!";
                    return response()->json($data);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to Buy voip!";
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
    	return (new DataAction)->DeleteData(BuyVoip::class,'buy_voip_id',$id);
    }
}
