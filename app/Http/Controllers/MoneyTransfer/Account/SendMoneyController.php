<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\SendMoney\SendMoney;
    use App\Http\Models\MoneyTransfer\SetupMaster\DeliveryMethod;
    use App\Http\Models\MoneyTransfer\Currency\Currency;
    use App\Http\Models\MoneyTransfer\Currency\ExchangeRate;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;

    class SendMoneyController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function send_money(Request $request){
            // $input = $request->all();
            // $user = Auth::user();
            // if(Account::checkHasAccountNo($input['account_no'])){
            //     $account_no = $input['account_no'];//Account::fetchAccountById($input['account_id'],'account_no');
            //     if(common::calculateExchangeCurrencyAccount($account_no,$input['receiving_currency'],$input['sending_amount'])){
            //         if(Account::checkTransactionAccount($input['account_id'],$input['sending_amount'])){
            //             $account_master = AccountMaster::select('account_master_id')
            //                                             ->where('user_id',$user->id)
            //                                             ->first();
            //             $token =  $user->createToken('Token Name')->accessToken;
            //             $input['delivery_method_id']=$input['delivery_method_id'];
            //             $DeliveryMethod = DeliveryMethod::select('name')->where('delivery_method_id',$input['delivery_method_id'])->first();
            //             $Currency = Currency::select('title')->where('currency_id',$input['receiving_currency'])->first();
            //             $input['delivery_method']=$DeliveryMethod->name;
            //             $input['receiving_currency_name']=$Currency->title;
            //             $input['receiving_amount']=$input['sending_amount'];
            //             // will send to merchant account no as wing or account bank
            //             $input['send_to_merchant_account_no'] = $input['send_to_merchant_account_no'];
            //             // $input['exchange_amount'] = common::convertCurrency($input['receiving_currency'],$input['sending_amount']); 
            //             // $SendMoney = SendMoney::create($input);
            //             $security_number = common::AccountMobileLog($account_master->merchant_id,$token);
            //             $data['success'] = true;
            //             $data['security_key'] = $security_number;
            //             $data['message'] = "Please, Confirm Security Code.";
            //             $data['send_money_information'] = $input;
            //             return response()->json($data, 200);
            //         }else{
            //             $data['success'] = false;
            //             $data['message'] = "Send money failed, You reached to maximum transaction rule!";
            //             return response()->json($data);
            //         }
            //     }else{
            //         $data['success'] = false;
            //         $data['message'] = "Send money fail, Insuficient balance!";
            //         return response()->json($data, 404);
            //     }
            // }else{
            //     $data['success'] = false;
            //     $data['message'] = "Fail to send money, or no such account!";
            //     return response()->json($data, 404); 
            // }
            $input = $request->all();
            $user = Auth::user();
            if(Account::checkHasAccountNo($input['account_no'])){
                $account_no = $input['account_no'];//Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($account_no,$input['sending_amount'])){
                    if(Account::checkTransactionAccount(Account::fetchAccount($input['account_no'],'account_id'),$input['sending_amount'])){
                        $token =  $user->createToken('Token Name')->accessToken;
                        $account_master = AccountMaster::select('account_master_id')
                                                        ->where('user_id',$user->id)
                                                        ->first();
                        $input['receiving_amount'] = common::CalculateExchangeRate($input['sending_amount'],Account::getCurrencyByAccountNo($input['account_no'],'currency_id'),Currency::GetCurrencyBaseId($input['receiving_currency'],'currency_id'));
                        $input['sending_currency'] = Account::getCurrencyByAccountNo($input['account_no'],'code');
                        $input['exchange_rate'] = Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountNo($input['account_no'],'currency_id'));
                        $input['sending_amount'] = $input['sending_amount'];
                        // $input['charge_fee'] = common::getAmountConvertCurrency(0,Account::getCurrencyByAccountNo($input['account_no'],'currency_id'),config_currency);
                        // will send to merchant account no as wing or account bank
                        // $input['send_to_merchant_account_no'] = $input['send_to_merchant_account_no'];
                        // Do Transaction By Account
                        $message = "Send Money";
                        $transaction_type = 'Send Money';
                        $sending_amount = 0;
                        // if($input['is_received']==true){
                        //     $sending_amount = $input['sending_amount'];
                        //     $input['status'] = true;
                        // }
                        $security_number = common::AccountMobileLog($account_master->merchant_id,$token);
                        $data['success'] = true;
                        $data['security_key'] = $security_number;
                        $data['message'] = "Please, Confirm Security Code.";
                        $data['send_money_information'] = $input;
                        return response()->json($data);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Send money failed, You reached to maximum transaction rule!";
                        return response()->json($data);
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Send money fail, Insuficient balance!";
                    return response()->json($data, 404);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to send money, no such account!";
                return response()->json($data, 404); 
            }
        }

        public function confirm_send_money(Request $request){
            $input = $request->all();
            $user = Auth::user();
            if(Account::checkHasAccountNo($input['account_no'])){
                $account_no = $input['account_no'];//Account::fetchAccountById($input['account_id'],'account_no');
                if(common::calculateSaveAccount($account_no,$input['sending_amount'])){
                    if(Account::checkTransactionAccount(Account::fetchAccount($input['account_no'],'account_id'),$input['sending_amount'])){
                        // $account_master = AccountMaster::select('account_master_id')
                        //                                 ->where('user_id',$user->id)
                        //                                 ->first();
                        // $input['delivery_method_id']=$input['delivery_method_id'];
                        // $DeliveryMethod = DeliveryMethod::select('name')->where('delivery_method_id',$input['delivery_method_id'])->first();
                        // $Currency = Currency::select('title')->where('currency_id',$input['receiving_currency'])->first();
                        // $input['delivery_method']=$DeliveryMethod->name;
                        // $input['receiving_currency_name']=$Currency->title;
                        // $input['receiving_currency']=$Currency->title;
                        // $input['receiving_amount']=$input['sending_amount'];
                        // // will send to merchant account no as wing or account bank
                        // $input['send_to_merchant_account_no'] = $input['send_to_merchant_account_no'];
                        // // $input['exchange_amount'] = common::convertCurrency($input['receiving_currency'],$input['sending_amount']); 
                        // // Do Transaction By Account
                        // $message = "Send Money";
                        // $transaction_type = "Send Money";
                        // common::SaveNotification($transaction_type, $account_no, $message, $input['sending_amount'], $Currency->title, $input['remark']);
                        // common::DoTransactionByAccount($account_master->account_master_id,$account_no,$input['sending_amount'],$message,$transaction_type);
                        // // send money
                        // $SendMoney = SendMoney::create($input);
                        // $data['success'] = true;
                        // $data['message'] = "Send Money Successfully.";
                        // $data['send_money_information'] = $input;
                        // return response()->json($data, 200);
                        $input['account_id'] = Account::fetchAccount($input['account_no'],'account_id');
                        $input['receiving_amount'] = common::CalculateExchangeRate($input['sending_amount'],Account::getCurrencyByAccountNo($input['account_no'],'currency_id'),Currency::GetCurrencyBaseId($input['receiving_currency'],'currency_id'));
                        $input['receiving_currency'] = Currency::GetCurrencyBaseId($input['receiving_currency'],'code');
                        // dd(Currency::GetCurrencyBaseId($input['receiving_currency'],'code'));
                        $input['sending_currency'] = Account::getCurrencyByAccountNo($input['account_no'],'code');
                        $input['exchange_rate'] = Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountNo($input['account_no'],'currency_id'));
                        $input['sending_amount'] = $input['sending_amount'];
                        // $input['charge_fee'] = common::getAmountConvertCurrency($input['charge_fee'],Account::getCurrencyByAccountNo($input['account_no'],'currency_id'),config_currency);
                        // will send to merchant account no as wing or account bank
                        $input['send_to_merchant_account_no'] = $input['send_to_merchant_account_no'];
                        // Do Transaction By Account
                        $message = "Send Money";
                        $transaction_type = 'Send Money';
                        $sending_amount = 0;
                        common::SaveNotification($transaction_type, $account_no, $message, $input['sending_amount'], $input['receiving_currency'], $input['remark']);
                        common::DoTransactionByAccount(Account::fetchAccount($input['account_no'],'account_master_id'),$account_no,$sending_amount,$message,$transaction_type);
                        // send money
                        $SendMoney = SendMoney::create($input);
                        $data['success'] = true;
                        $data['message'] = "Send Money Successfully.";
                        $data['data'] = $SendMoney;
                        return response()->json($data);
                    }else{
                        $data['success'] = false;
                        $data['message'] = "Send money failed, You reached to maximum transaction rule!";
                        return response()->json($data);
                    }
                }else{
                    $data['success'] = false;
                    $data['message'] = "Send money fail, Insuficient balance!";
                    return response()->json($data, 404);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Failed to send money, no such account!";
                return response()->json($data, 404); 
            }
        }
    }