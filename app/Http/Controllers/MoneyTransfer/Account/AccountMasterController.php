<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\User;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountTransaction;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountCredit;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMobileLog;
    use App\Http\Models\MoneyTransfer\Voucher\BuyVoip;
    use App\Http\Models\MoneyTransfer\Voucher\BuyVoucher;
    use App\Http\Models\MoneyTransfer\SendMoney\SendMoney;
    use App\Http\Models\MoneyTransfer\TransferMoney\TransferMoney;
    use App\Http\Models\MoneyTransfer\Currency\Currency;
    use App\Http\Models\MoneyTransfer\User\UserDevice;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;

    class AccountMasterController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */

        public function generateSecureNum(){
            $random = rand(100000, 999999);
            return $random;
        }
        public function login(Request $request){
            if(Auth::attempt(['username' => request('country_code').request('telephone'), 'password' => request('password')])){
                $user = Auth::user();
                $sec_user = Auth::user();
                $data['general_profile'] = $sec_user;
                $account_master = AccountMaster::where('user_id',$user->id)->first();
                $token =  $user->createToken('Token Name')->accessToken;
                // $AccountMobileLog = AccountMobileLog::create([
                //     'merchant_no' => $account_master->merchant_id,
                //     'token_id' => $token,
                //     'ip' => '192.168.160.100',
                //     'expire_date' => date('Y-m-d H:i:s'),
                //     'security_key' => '123456',//$this->generateSecureNum(),
                //     'date_added ' => date('Y-m-d H:i:s')
                // ]);
                $this->saveTokenDevice($user, $request);
                $security_number = common::AccountMobileLog($account_master->merchant_id,$token);
                return response()->json(
                            [
                                'success'=>true,
                                'message'=>'Security key will be sent to you soon.',
                                'security_key'=>$security_number,
                                'PID'=>'DLUyc4VN5gdK2M4Hf5ZlH7dmEoO2DcowALRhzTrf',
                                'token'=>$token,
                                'data' => $data
                            ], 200);
                //return response()->json(['success'=>true,'security_key'=>$security_number,'message'=>'Security key will be sent to you soon.','token'=>$token,'data' => $data], 200);
            }
            else{
                return response()->json(['success'=>false,'message'=>'You have login failed.'], 401);
            }
        }

        public function relogin(Request $request){
            if(Auth::attempt(['username' => request('country_code').request('telephone'), 'password' => request('password')])){
                $user = Auth::user();
                $data['general_profile'] = $user;
                $data['account_master'] = AccountMaster::where('user_id',$user->id)->first();
                $account_master = AccountMaster::where('user_id',$user->id)->first();
                $token =  $user->createToken('Token Name')->accessToken;
                $this->saveTokenDevice($user, $request);
                return response()->json(
                            [
                                'success'=>true,
                                'message'=>'Login successfully.',
                                'token'=>$token,
                                'data' => $data
                            ], 200);
            }
            else{
                return response()->json(['success'=>false,'message'=>'You have login failed.'], 401);
            }
        }

        public function logout(Request $request)
        {
            if(Auth::attempt(['username' => request('country_code').request('telephone'), 'password' => request('password')])){
                $user = Auth::user();
                DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
                return response()->json(
                    [
                        'success'=>true,
                        'message'=>'Logout successfully.'
                    ], 200);
            }else{
                return response()->json(['success'=>false,'message'=>'Failed to logout!']);
            }
        }

        public function ConfirmSecurityKey(Request $request){
            $input = $request->all();
            $user = Auth::user();
            $data['general_profile'] = $user;
            $data['account_master'] = AccountMaster::where('user_id',$user->id)->first();
            $token =  $user->createToken('Token Name')->accessToken;
            $AccountMobileLog = AccountMobileLog::Where('security_key',$input['security_key'])->where('status',0)->first();
            AccountMobileLog::where('security_key',$input['security_key'])->update(['status'=>1]);
            if($AccountMobileLog){
                return response()->json(['success'=>true,'message'=>'Login Successfully.','token'=>$token, 'data' => $data], 200);
            }else{
                return response()->json(['success'=>false, 'message'=>'This security is wrong!']);
            }
        }

        public function _register(Request $request){
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'telephone_1' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            if($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401); 
            }

            $input = $request->all();
            if($this->checkHasAccount($input['email'])==0){
                // // $input['password'] = bcrypt($input['password']);
                // // user account
                // $User = User::create([
                //     'first_name'=>$input['first_name'],
                //     'last_name'=>$input['last_name'],
                //     'telephone_1'=>$input['telephone_1'],
                //     'username'=>$input['email'],
                //     'email'=>$input['email'],
                //     'password'=>bcrypt(trim($input['password'])),
                //     'user_group_id'=>6,
                // ]);
                // // account master
                // $AccountMaster = AccountMaster::create([
                //     'user_id'=>$User->id,
                //     'merchant_id'=>rand(10000000, 99999999),
                //     //'deviceId'=>$input['deviceId']
                // ]);
                // // account
                // $Account = Account::create([
                //     'account_type_id'=>1,
                //     'currency_id'=>1,//$input['currency_id'],
                //     'account_master_id'=>$AccountMaster->account_master_id
                // ]);
                // $token =  $User->createToken('Token Name')->accessToken;
                return response()->json(['success' => true, 'message' => 'Confirm security code has been sent to your phone number.','security_key'=>'1234', 'register_info'=>$input], 200);
            }else{
                return response()->json(['success' => false, 'message' => 'This account is already registered!'], 401);
            }
        }

        public function _confirm_register(Request $request){
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'telephone_1' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            if($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401); 
            }

            $input = $request->all();
            if($this->checkHasAccount($input['email'])==0){
                // $input['password'] = bcrypt($input['password']);
                // user account
                $User = User::create([
                    'first_name'=>$input['first_name'],
                    'last_name'=>$input['last_name'],
                    'telephone_1'=>$input['telephone_1'],
                    'username'=>$input['email'],
                    'email'=>$input['email'],
                    'password'=>bcrypt(trim($input['password'])),
                    'user_group_id'=>6,
                ]);
                // account master
                $AccountMaster = AccountMaster::create([
                    'user_id'=>$User->id,
                    'merchant_name'=>$input['first_name'].' '.$input['last_name'],
                    'merchant_id'=>rand(10000000, 99999999),
                    //'deviceId'=>$input['deviceId']
                ]);
                // account
                $Account = Account::create([
                    'account_type_id'=>1,
                    'account_master_id'=>$AccountMaster->account_master_id,
                    'transaction_rule_id'=>1,
                    'currency_id'=>1,//$input['currency_id'],
                    'account_code'=>rand(10000000, 99999999),
                    'account_no'=>rand(10000000, 99999999),
                    'is_active'=>1,
                    'is_default'=>1
                ]);
                //add static account credit
                $AccountCredit = AccountCredit::create([
                    'account_id'=>$Account->account_id,
                    'deposit_date'=>date("Y-m-d H:i:s"),
                    'credit_amount'=>floatval(100),
                    'credit_amount_history'=>floatval(100),
                    'status'=>1,
                    'is_active'=>1
                    // 'deposit_by'=>Auth::user()->id
                ]);
                    
                $token =  $User->createToken('Token Name')->accessToken;
                return response()->json(['success' => true, 'message' => 'Register successfully.', 'token' => $token,'register_info'=>$User], 200);
            }else{
                return response()->json(['success' => false, 'message' => 'This account is already registered!'], 401);
            }
        }

        public function register(Request $request){
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'country_code' => 'required',
                'telephone_1' => 'required',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            if($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401); 
            }
            $input = $request->all();
            $telephone = $input['country_code'].$input['telephone_1'];
            if($this->checkHasAccount($input['email'],$telephone)==0){
                // $input['password'] = bcrypt($input['password']);
                // user account
                $User = User::create([
                    'first_name'=>$input['first_name'],
                    'last_name'=>$input['last_name'],
                    'telephone_1'=>$telephone,
                    'username'=>$telephone,
                    'country_id'=>config_country_id,
                    'email'=>$input['email'],
                    'password'=>bcrypt(trim($input['password'])),
                    'user_group_id'=>config_user_group_regsiter,
                ]);
                $this->saveTokenDevice($User, $request);
                // account master
                $AccountMaster = AccountMaster::create([
                    'user_id'=>$User->id,
                    'merchant_name'=>$input['first_name'].' '.$input['last_name'],
                    'merchant_id'=>rand(10000000, 99999999),
                    'is_active'=>1,
                    //'deviceId'=>$input['deviceId']
                ]);
                // // account
                $Account = Account::create([
                    'account_type_id'=>1,
                    'account_master_id'=>$AccountMaster->account_master_id,
                    'transaction_rule_id'=>1,
                    'credit_amount'=>0,
                    'currency_id'=>config_currency,
                    'account_code'=>rand(10000000, 99999999),
                    'account_no'=>rand(10000000, 99999999),
                    'is_active'=>1,
                    'is_default'=>1,
                    'status'=>1
                ]);
                // //add static account credit
                // $AccountCredit = AccountCredit::create([
                //     'account_id'=>$Account->account_id,
                //     'deposit_date'=>date("Y-m-d H:i:s"),
                //     'credit_amount'=>floatval(100),
                //     'credit_amount_history'=>floatval(100),
                //     'status'=>1,
                //     'is_active'=>1
                //     // 'deposit_by'=>Auth::user()->id
                // ]);
                $token =  $User->createToken('Token Name')->accessToken;
                return response()->json(['success' => true, 'message' => 'Register successfully.', 'token' => $token,'register_info'=>$User], 200);
            }else{
                return response()->json(['success' => false, 'message' => 'This account is already registered!'], 401);
            }
        }

        public function del_account(Request $request){
            $input = $request->all();
            $User = User::where('telephone_1',$input['telephone'])->first();
            if($User){
                $AccountMaster = AccountMaster::where('user_id',$User->id)->first();
                $Account = Account::where('account_master_id',$AccountMaster->account_master_id)->first();
                if($Account){
                    AccountCredit::where('account_id',$Account->account_id)->delete();
                }
                User::where('id',$User->id)->delete();
                AccountMaster::where('user_id',$User->id)->delete();
                Account::where('account_master_id',$AccountMaster->account_master_id)->delete();
                // AccountCredit::where('account_id',$Account->account_id)->delete();
                // AccountTransaction::where('account_no',$Account->account_no)->delete();
                // SendMoney::where('account_id',$Account->account_id)->delete();
                // TransferMoney::where('transfer_from_account_no',$Account->account_id)->delete();
                // BuyVoip::where('account_no',$Account->account_no)->delete();
                // BuyVoucher::where('from_account_no',$Account->account_no)->delete();
                return response()->json(['success' => true, 'message' => 'Delete successfully.'], 200);
            }else{
                return response()->json(['success' => false, 'message' => 'Not account found!'], 200);
            }
        }

        public function checkHasAccount($email,$telephone){
            $user = User::where('telephone_1',$telephone)->first();
            $result = 1;
            if($user){
                if($user->telephone_1==$telephone){
                    $result = 1; 
                }else{
                    if($user->email==$email && $user->email!=null){
                        $result = 1;
                    }else{
                        $result = 0; 
                    }
                }
            }else{
                $user_has_email = User::where('email',$email)->first();
                if($user_has_email){
                    if($user_has_email->email==$email && $user_has_email->email!=null){
                        $result = 1;
                    }else{
                        $result = 0;
                    }
                }else{
                    $result = 0; 
                }
            }
            return $result;
        }

        public function getMasterAccount(){
            $sec_user = Auth::user();
            $AccountMaster = AccountMaster::where('user_id',$sec_user->id)->first();
            // dd($AccountMaster->account_master_id);
            // $AccountMaster = array(
            //     'account_master_id'=>$sec_user->AccountMaster->account_master_id,
            //     'user_id'=>$sec_user->AccountMaster->user_id,
            //     'deviceId'=>$sec_user->AccountMaster->deviceId,
            //     'finger_print'=>$sec_user->AccountMaster->finger_print,
            //     'expired_account'=>$sec_user->AccountMaster->expired_account,
            //     'merchant_id'=>$sec_user->AccountMaster->merchant_id,
            //     'is_active'=>$sec_user->AccountMaster->is_active
            // );
            $data['success'] = true;
            $data['message'] = "You have retrieve data successfully.";
            $data['master_account'] = $AccountMaster;
            $data['general_profile'] = $sec_user;
            $data['saving_account'] = $this->getSavingAccount(intval($AccountMaster->account_master_id));
            $data['total'] = count($this->getSavingAccount(intval($AccountMaster->account_master_id)));
            return response()->json($data, 200);
        }

        public function getAccountTransaction(){
            $user = Auth::user();
            $account_master = AccountMaster::where('user_id',$user->id)->first();
            $AccountTransaction = AccountTransaction::Where('account_master_id',$account_master->account_master_id)->get();
            return response()->json(['success'=>true,'message'=>'Success','data'=>$AccountTransaction],200);
        }

        public function getTransactionByAccountId(Request $request){
            $input = $request->all();
            $account_id = $input['account_id'];
            $account_no = Account::fetchAccountById($account_id,'account_no');
            $user = Auth::user();
            $account_master = AccountMaster::where('user_id',$user->id)->first();
            $AccountTransaction = AccountTransaction::Where('account_master_id',$account_master->account_master_id)
                                                        ->where('account_no',$account_no)
                                                        ->get();
            return response()->json(['success'=>true,'message'=>'Success','data'=>$AccountTransaction],200);
        }

        public function getSavingAccount($master_account_id){
            $Accounts = Account::where('account_master_id',$master_account_id)->get();
            $data = array();
            foreach ($Accounts as $Account){
                if(Currency::GetExchangeRate($Account->currency_id)){
                    $GetExchangeRate = Currency::GetExchangeRate($Account->currency_id);
                    $buy_in = $GetExchangeRate->buy_in;
                    $sell_out = $GetExchangeRate->sell_out;
                }else{
                    $GetExchangeRate = 0;
                    $buy_in = 0;
                    $sell_out = 0;
                }
                $data[] = array(
                    'account_id'=>$Account->account_id,
                    'currency_id'=>floatval($Account->currency_id),
                    'buy_in' => $buy_in,
                    'sell_out' => $sell_out,
                    'account_type_id'=>$Account->account_type_id,
                    'account_master_id'=>$Account->account_master_id,
                    'total_saving_amount'=> $Account->credit_amount,//$this->getTotalDeposit($Account->account_id),
                    'transaction_rule_id'=>$Account->transaction_rule_id,
                    'account_code'=>$Account->account_code,
                    'account_no'=>$Account->account_no,
                    'currency_code'=>$Account->Currency->code,
                    'is_default'=>$Account->is_default,
                    'is_active'=>$Account->is_active,
                    'order_level'=>$Account->order_level,
                    'created_by'=>$Account->created_by,
                    'modified_by'=>$Account->modified_by,
                );
            }
            return $data;
        }

        public function getTotalDeposit($account_id){
            $amount = AccountCredit::where('account_id',$account_id)
                                                  ->where('status',1)
                                                  ->where('is_active',1)
                                                  ->sum('credit_amount');
            return $amount;
        }

        public function saveTokenDevice($user,$data){
            $input['device_id'] = $data['device_id'];
            $input['device_type'] = $data['device_os'];
            $input['sec_user_id'] = $user->id;
            $input['username'] = $user->username;
            if($this->checkHasUser($user->id)){
                UserDevice::create($input);
            }else{
                UserDevice::where('sec_user_id', $user->id)->update($input);
            }
        }

        public function checkHasUser($userId){
            $boolen = false;
            $UserDevice = UserDevice::where('sec_user_id', $userId)->first();
            if($UserDevice){
                $boolen = false;
            }else{
                $boolen = true;
            }
            return $boolen;
        }

    }