<?php

namespace App\Http\Controllers\MoneyTransfer\Voucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Voucher\BuyVoucher;
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

class BuyVoucherController extends Controller
{
    public function index()
    {
        $BuyVoucher = BuyVoucher::OrderBy('buy_voucher_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$BuyVoucher,'total'=>count($BuyVoucher)]);
    }
    public function store(Request $request)
    {   
        $input = $request->all();
        // dd($input);
        $Product = Product::Where('product_id',$input['product_id'])->first();
        if($Product && $Product->quantity>=$request->quantity){
            if(Account::checkHasAccount(Account::fetchAccount($input['from_account_no'],'account_id'))){
                // $account_no = Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($input['from_account_no'],$Product->price)){
                    if(Account::checkTransactionAccount(Account::fetchAccount($input['from_account_no'],'account_id'),$Product->price)){
                        $account_master_id = Account::fetchAccount($input['from_account_no'],'account_master_id');
                        $data=(new BuyVoucher)->getFillable();
                        $data=$request->only($data);
                        $data['amount_top_up'] = common::getAmountConvertCurrency($Product->price,Account::getCurrencyByAccountId(Account::fetchAccount($input['from_account_no'],'account_id'),'currency_id'),config_currency)*floatval($input['quantity']);
                        // $data['amount_top_up'] = $Product->price;
                        $data['currency'] = Account::getCurrencyByAccountNo($input['from_account_no'],'code');
                        $data['transfer_date'] = date("Y-m-d");
                        $condition=[
                            //'key'=>$data['key']
                        ];
                        $message = "Purchase Vouchers";
                        $transaction_type = 'Purchase Vouchers';
                        $sending_amount = 0;
                        common::DoTransactionByAccount($account_master_id,$input['from_account_no'],$sending_amount,$message,$transaction_type);
                        return (new DataAction)->StoreData(BuyVoucher::class,$condition,'',$data);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Buy voucher failed, You reached to maximum transaction rule!";
                        return response()->json($data); 
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Buy voucher failed, Insuficient balance!";
                    return response()->json($data);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to Buy voucher!";
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
        $BuyVoucher = BuyVoucher::where("buy_voucher_id",$id)->first();
        $Account = Account::where('account_no',$BuyVoucher->from_account_no)->first();
        $data = array(
            'category_id' => $BuyVoucher->category_id,
            'product_id' => $BuyVoucher->product_id,
            'quantity' => $BuyVoucher->quantity,
            'account_master_id' => $Account->account_master_id,
            'from_account_no' => $BuyVoucher->from_account_no,
            'amount_top_up' => $BuyVoucher->amount_top_up,
            'currency' => $BuyVoucher->currency,
            'country_id' => $BuyVoucher->country_id,
            'pay_to_voucher' => $BuyVoucher->pay_to_voucher,
            'telephone' => $BuyVoucher->telephone,
            'transfer_date' => $BuyVoucher->transfer_date,
            'message' => $BuyVoucher->message,
            'status' => $BuyVoucher->status
        );
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($BuyVoucher)]);
        // return (new DataAction)->EditData(BuyVoucher::class,$id);
    }
    public function edit($id)
    {
        $BuyVoucher = BuyVoucher::where("buy_voucher_id",$id)->first();
        $Account = Account::where('account_no',$BuyVoucher->from_account_no)->first();
        $data = array(
            'category_id' => $BuyVoucher->category_id,
            'product_id' => $BuyVoucher->product_id,
            'quantity' => $BuyVoucher->quantity,
            'account_master_id' => $Account->account_master_id,
            'from_account_no' => $BuyVoucher->from_account_no,
            'amount_top_up' => $BuyVoucher->amount_top_up,
            'currency' => $BuyVoucher->currency,
            'country_id' => $BuyVoucher->country_id,
            'pay_to_voucher' => $BuyVoucher->pay_to_voucher,
            'telephone' => $BuyVoucher->telephone,
            'transfer_date' => $BuyVoucher->transfer_date,
            'message' => $BuyVoucher->message,
            'status' => $BuyVoucher->status
        );
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($BuyVoucher)]);
    }
    public function update(Request $request,$id)
    {
        // $data=(new BuyVoucher)->getFillable();
        // $data=$request->only($data);
        // // if(@$data['image']){
        // //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // // }
        $input = $request->all();
        $Product = Product::Where('product_id',$input['product_id'])->first();
        if($Product && $Product->quantity>=$request->quantity){
            if(Account::checkHasAccount(Account::fetchAccount($input['from_account_no'],'account_id'))){
                // $account_no = Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($input['from_account_no'],$Product->price)){
                    if(Account::checkTransactionAccount(Account::fetchAccount($input['from_account_no'],'account_id'),$Product->price)){
                        $account_master_id = Account::fetchAccount($input['from_account_no'],'account_master_id');
                        $data=(new BuyVoucher)->getFillable();
                        $data=$request->only($data);
                        $total_amount = common::getAmountConvertCurrency($Product->price,Account::getCurrencyByAccountId(Account::fetchAccount($input['from_account_no'],'account_id'),'currency_id'),config_currency)*floatval($input['quantity']);
                        $data['amount_top_up'] = $total_amount;
                        $data['currency'] = Account::getCurrencyByAccountNo($input['from_account_no'],'code');
                        $data['transfer_date'] = date("Y-m-d");
                        $condition=[
                            //'key'=>$data['key']
                        ];
                        $message = "Purchase Vouchers";
                        $transaction_type = 'Purchase Vouchers';
                        $sending_amount = 0;
                        if(isset($input['status']) && $input['status']==1){
                            $sending_amount = $total_amount;
                            $getUserId = AccountMaster::getMerchant($account_master_id,'user_id');
                            $User = User::Where('id',$getUserId)->first();
                            $OrderLastId = Order::insertGetId([
                                'invoice_no' => config_invoice_prefix.rand(11111111, 99999999),
                                'account_id' => Account::fetchAccount($input['from_account_no'],'account_id'),
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
                                'comment' => 'Buy Voucher',
                                'total' => $Product->price,
                                'order_status_id' => 1,
                                'commission' => 0,
                                'currency_id' => Account::getCurrencyByAccountNo($input['from_account_no'],'currency_id'),
                                'currency_code' => Account::getCurrencyByAccountNo($input['from_account_no'],'code'),
                                'currency_value' => Account::getCurrencyByAccountNo($input['from_account_no'],'value'),
                                'exchange_rate' => Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountNo($input['from_account_no'],'currency_id')),
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
                                $data['message'] = "Buy voucher failed, You reached to maximum transaction rule!";
                                return response()->json($data);
                            }
                        }
                        
                        common::DoTransactionByAccount($account_master_id,$input['from_account_no'],$sending_amount,$message,$transaction_type);
                        return (new DataAction)->UpdateData(BuyVoucher::class,$data,'buy_voucher_id',$id);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Buy voucher failed, You reached to maximum transaction rule!";
                        return response()->json($data); 
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Buy voucher failed, Insuficient balance!";
                    return response()->json($data);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to Buy voucher!";
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
    	return (new DataAction)->DeleteData(BuyVoucher::class,'buy_voucher_id',$id);
    }
}
