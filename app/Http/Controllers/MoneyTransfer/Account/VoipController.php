<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\Voucher\BuyVoucher;
    use App\Http\Models\MoneyTransfer\Voucher\Voucher;
    use App\Http\Models\MoneyTransfer\Voucher\AmountTopUp;
    use App\Http\Models\MoneyTransfer\Voucher\BuyVoip;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;

    class VoipController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function buy_voip(Request $request){
            $input = $request->all();
            $Account = Account::where('account_no',$input['account_no'])->where('is_active',1)->first();
            $calculateSaveAccount = common::calculateSaveAccount($input['account_no'],$input['amount']);
            $user = Auth::user();
            if($calculateSaveAccount){
                if(Account::checkHasAccount($Account->account_id)){
                    $account_master = AccountMaster::select('account_master_id')
                                                    ->where('user_id',$user->id)
                                                    ->first();
                    $token =  $user->createToken('Token Name')->accessToken;
                    $security_number = common::AccountMobileLog($account_master->merchant_id,$token);
                    $input['status']=0;
                    $data['success'] = true;
                    $data['message'] = "Confirm Security Code.";
                    $data['security_key'] = $security_number;
                    $data['data'] = $input;
                    return response()->json($data, 200);
                }else{
                    $data['success'] = false;
                    $data['message'] = "Buy VoIP false!";
                    $data['data'] = $input;
                    return response()->json($data, 404);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Buy VoIP false, Insuficient Balance!";
                $data['data'] = $input;
                return response()->json($data, 404);
            }
        }

        public function confirm_buy_voip(Request $request){
            $input = $request->all();
            $Account = Account::where('account_no',$input['account_no'])->where('is_active',1)->first();
            $calculateSaveAccount = common::calculateSaveAccount($input['account_no'],$input['amount']);
            $user = Auth::user();
            if($calculateSaveAccount){
                if(Account::checkHasAccount($Account->account_id)){
                    $account_master = AccountMaster::select('account_master_id')
                                        ->where('user_id',$user->id)
                                        ->first();
                                        
                    $input['status']=0;
                    // Do Transaction By Account
                    $transaction_type = 'Voip';
                    $message = "Buy VoIP";
                    $input['date_added'] = date('Y-m-d');
                    $input['date_modified'] = date('Y-m-d');
                    common::DoTransactionByAccount($account_master->account_master_id,$input['account_no'],$input['amount'],$message,$transaction_type);
                    $BuyVoip = BuyVoip::create($input);
                    $data['success'] = true;
                    $data['message'] = "Buy VoIP Successfully.";
                    $data['data'] = $BuyVoip;
                    return response()->json($data, 200);
                }else{
                    $data['success'] = false;
                    $data['message'] = "Buy VoIP false.";
                    $data['data'] = $input;
                    return response()->json($data, 404);
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Buy VoIP false Or Insuficient Balance!";
                $data['data'] = $input;
                return response()->json($data, 404);
            }
        }

        public function getVoucher(){
            $Vouchers=Voucher::all();
            $VoucherArr = array();
            foreach($Vouchers as $Voucher){
                $VoucherArr[] = array(
                    'voucher_id'=>$Voucher->voucher_id,
                    'name'=>$Voucher->name,
                    'image'=>config_url.$Voucher->image,
                    'order_level'=>$Voucher->order_level
                );
            }
            return response()->json($VoucherArr,200);
        }

        public function getAmountTopUp(){
            $AmountTopUp=AmountTopUp::OrderBy('order_level')->get();
            return response()->json($AmountTopUp,200);
        }
    }