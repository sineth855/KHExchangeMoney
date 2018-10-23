<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
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

    class VoucherController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function buy_voucher(Request $request){
            $input = $request->all();
            $user = Auth::user();
            $calculateSaveAccount = common::calculateSaveAccount($input['from_account_no'],$input['amount_top_up']);
            if($calculateSaveAccount){
                $account_master = AccountMaster::select('account_master_id')
                                                ->where('user_id',$user->id)
                                                ->first();
                $token =  $user->createToken('Token Name')->accessToken;
                $security_number = common::AccountMobileLog($account_master->merchant_id,$token);
                $input['status']=0;
                $data['success'] = true;
                $data['security_key'] = $security_number;
                $data['message'] = "Confirm Security Code.";
                $data['data'] = $input;
                return response()->json($data, 200);
            }else{
                $data['success'] = false;
                $data['message'] = "Buy Voucher false.";
                $data['data'] = $input;
                return response()->json($data, 200);
            }
        }

        public function confirm_buy_voucher(Request $request){
            $input = $request->all();
            $user = Auth::user();
            $calculateSaveAccount = common::calculateSaveAccount($input['from_account_no'],$input['amount_top_up']);
            if($calculateSaveAccount){
                $account_master = AccountMaster::select('account_master_id')
                                                ->where('user_id',$user->id)
                                                ->first();
                $input['status']=0;
                $data['success'] = true;
                $data['message'] = "Buy Voucher Successfully.";
                $data['data'] = $input;
                $input['transfer_date'] = date('Y-m-d');
                // Do Transaction By Account
                $transaction_type = 'Voucher';
                $message = "Buy Voucher";
                common::DoTransactionByAccount($account_master->account_master_id,$input['from_account_no'],$input['amount_top_up'],$message,$transaction_type);
                $BuyVoucher = BuyVoucher::create($input);
                return response()->json($data, 200);
            }else{
                $data['success'] = false;
                $data['message'] = "Buy Voucher false.";
                $data['data'] = $input;
                return response()->json($data, 200);
            }
        }

        public function getVoucher(Request $request){
            $user = Auth::user();
            $Vouchers=Voucher::Where('country_id',$user->country_id)->get();
            if(count($Vouchers)>0){
                $VoucherArr = array();
                foreach($Vouchers as $Voucher){
                    $VoucherArr[] = array(
                        'voucher_id'=>$Voucher->voucher_id,
                        'name'=>$Voucher->name,
                        'image'=>config_url.$Voucher->image,
                        'order_level'=>$Voucher->order_level
                    );
                }
                return response()->json(['success'=>true,'message'=>'Success','data'=>$VoucherArr],200);
            }else{
                return response()->json(['success'=>false,'message'=>'Fail no voucher found!'],404);
            }
        }

        public function getAmountTopUp(Request $request){
            $input = $request->all();
            $AmountTopUp=AmountTopUp::OrderBy('order_level')->Where('voucher_id',$input['voucher_id'])->where('voucher_id',$input['voucher_id'])->get();
            if(count($AmountTopUp)>0){
                return response()->json(['success'=>true,'message'=>'Success','data'=>$AmountTopUp],200);
            }else{
                return response()->json(['success'=>false,'message'=>'Fail no Amount Toup Up Found!'],404);
            }
        }

    }