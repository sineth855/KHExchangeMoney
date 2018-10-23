<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
    use App\Http\Models\MoneyTransfer\TransferMoney\TransferMoney;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;

    class TransferMoneyController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function transfer_money(Request $request){
            $input = $request->all();
            $user = Auth::user();
            $checkAccount = Account::checkAccount($input['transfer_from_account_no'],$input['transfer_to_account_no']);
            if($checkAccount){
                if(common::calculateSaveAccount($input['transfer_from_account_no'],$input['transfer_amount'])){
                    $Account = Account::Where('account_id',$input['transfer_from_account_no'])->first();
                    $account_master = AccountMaster::select('account_master_id')
                                                    ->where('user_id',$user->id)
                                                    ->first();
                    $token =  $user->createToken('Token Name')->accessToken;
                    $input['delivery_method'] = config_mobile_delivery_method;
                    $input['transfer_no']=rand(1000, 9999);
                    $input['receiving_amount']=$input['transfer_amount'];
                    $input['transfer_from_currency_id'] = $Account->currency_id;
                    $input['transfer_to_currency_id'] = Account::getCurrencyByAccountNo($input['transfer_to_account_no'],'currency_id');
                    $input['transfer_fee']=0;
                    $input['transfer_date'] = date('Y-m-d');
                    $input['remark'] = $input['remark'];
                    $input['created_by']=$user->id;
                    $security_number = common::AccountMobileLog($account_master->merchant_id,$token);
                    // $TransferMoney = TransferMoney::create($input);
                    $data['success'] = true;
                    $data['message'] = "Confirm Transfer.";
                    $data['security_key'] = $security_number;
                    $data['account_transferee_info'] = Account::GetAccountTransferee($input['transfer_to_account_no']);
                    // $data['send_money_information'] = $TransferMoney;
                    // common::calculateSaveAccount($input['transfer_from_account_no'],$input['transfer_amount'])
                    return response()->json($data, 200);
                }else{
                    $data['success'] = false;
                    $data['message'] = "Transfer account fail, Insuficient balance!";
                    return response()->json($data, 404);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Transfer account fail, no such account!";
                $data['transfer_info'] = $input;
                return response()->json($data, 404);
            }
        }

        public function confirm_transfer(Request $request){
            $input = $request->all();
            $user = Auth::user();
            $checkAccount = Account::checkAccount($input['transfer_from_account_no'],$input['transfer_to_account_no']);
            if($checkAccount){
                if(common::calculateSaveAccount($input['transfer_from_account_no'],$input['transfer_amount'])){
                    $account_master = AccountMaster::select('account_master_id')
                                                    ->where('user_id',$user->id)
                                                    ->first();
                    $token =  $user->createToken('Token Name')->accessToken;
                    $input['delivery_method'] = config_mobile_delivery_method;
                    $input['transfer_no']=rand(1000, 9999);
                    $input['receiving_amount']=$input['transfer_amount'];
                    $input['transfer_fee']=0;
                    $input['status']=1;
                    $input['transfer_date'] = date('Y-m-d');
                    $input['remark'] = $input['remark'];
                    $input['created_by']=$user->id;
                    // $TransferMoney = TransferMoney::create($input);
                    $data['success'] = true;
                    $data['message'] = "Transfer Successfully.";
                    $data['transfer_info'] = $input;
                    $message = "Transfer Money.";
                    $transaction_type = 'Transfer Money';
                    $DoTransactionByAccount = common::DoTransactionByAccount($account_master->account_master_id,$input['transfer_from_account_no'],$input['transfer_amount'],$message,$transaction_type);
                    common::DoUpdateAccountTransferTo($input['transfer_to_account_no'],$input['transfer_amount']);
                    $TransferMoney = TransferMoney::create($input);
                    $AccountMobileLog = common::AccountMobileLog($account_master->merchant_id,$token);
                    // Calculate amount of saving account from transfer
                    // common::calculateSaveAccount($input['transfer_from_account_no'],$input['transfer_amount']);
                    return response()->json($data, 200);
                }else{
                    $data['success'] = false;
                    $data['message'] = "Transfer account fail, Insuficient balance!";
                    return response()->json($data, 404);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Transfer account fail, no such account!";
                $data['transfer_info'] = $input;
                return response()->json($data, 404);
            }
        }

        // public function confirm_transfer(Request $request){
        //     $input = $request->all();
        //     $user_id = Auth::user()->id;
        //     $input['delivery_method'] = config_mobile_delivery_method;
        //     $input['transfer_no']=rand(1000, 9999);
        //     $input['receiving_amount']=$input['transfer_amount'];
        //     $input['transfer_fee']=0;
        //     $input['created_by']=$user_id;
        //     $input['transfer_from_currency_id']=1;
        //     $input['transfer_to_currency_id']=1;
        //     $data['success'] = true;
        //     $data['message'] = "Transfer Successfully.";
        //     $data['transfer_info'] = $input;
        //     $TransferMoney = TransferMoney::create($input);
        //     return response()->json($data, 200);
        // }
    }
    