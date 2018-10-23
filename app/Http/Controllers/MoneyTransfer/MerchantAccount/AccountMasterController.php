<?php

namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountCredit;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountTransaction;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountRule;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountType;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountWithdraw;
use App\Http\Models\MoneyTransfer\SendMoney\SendMoney;
use App\Http\Models\MoneyTransfer\TransferMoney\TransferMoney;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountLoan;
use App\Http\Models\MoneyTransfer\Currency\Currency;
use App\Http\Models\MoneyTransfer\User\User;
use Auth;
use App\Helpers\common;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class AccountMasterController extends Controller
{
    public function index()
    {
        $AccountMasterArr = $this->getData($account_master_id='');
        return response()->json(['success'=>true,'message'=>'Success','data'=>$AccountMasterArr,'total'=>count($AccountMasterArr)]);
    }
    public function getData($account_master_id=''){
        if($account_master_id=='')
            $AccountMaster=AccountMaster::OrderBy('date_modified','desc')->get();
        else
            $AccountMaster=AccountMaster::where('account_master_id',$account_master_id)->OrderBy('date_modified','desc')->get();

        $AccountMasterArr = array();
        foreach ($AccountMaster as $AM) {
            $Accounts = $AM->getAccount();
            $Account2Arr = array();
            foreach ($Accounts as $Account) {
                $AccountCreditInfo = AccountCredit::where('account_id',$Account->account_id)->first();
                $Account2Arr[] = array(
                    'account_no'=>$Account->account_no,
                    'account_code'=>$Account->account_code,
                    'currency'=>$Account->Currency->code,
                    'AccountCreditInfo'=>$AccountCreditInfo
                );
            }

            $AccountRule = AccountRule::all();
            $AccountRuleArr = array();
            foreach($AccountRule as $AR){
                $AccountRuleArr[] = array(
                    'value'=>$AR->account_rule_id,
                    'text'=>$AR->name
                );
            }

            $AccountType = AccountType::all();
            $AccountTypeArr = array();
            foreach($AccountType as $AT){
                $AccountTypeArr[] = array(
                    'value'=>$AT->account_type_id,
                    'text'=>$AT->name
                );
            }

            $Currency = Currency::all();
            $CurrencyArr = array();
            foreach($Currency as $C){
                $CurrencyArr[] = array(
                    'value'=>$C->currency_id,
                    'text'=>$C->title
                );
            }

            $AccountMasterArr[] = array(
                'user_id' => $AM->user_id,
                'account_master_id' => $AM->account_master_id,
                'password' => $AM->User->password,
                'merchant_name' => $AM->merchant_name,
                'merchant_id' => $AM->merchant_id,
                'is_active' => $AM->is_active,
                'username' => $AM->User->username,
                'first_name' => $AM->User->first_name,
                'last_name' => $AM->User->last_name,
                'email' => $AM->User->email,
                'dob' => $AM->User->dob,
                'code' => $AM->User->code,
                'telephone_1' => $AM->User->telephone_1,
                'telephone_2' => $AM->User->telephone_2,
                'address_1' => $AM->User->address_1,
                'address_2' => $AM->User->address_2,
                'expired_account' => $AM->expired_account,
                'company' => $AM->User->company,
                'country' => $AM->User->country,
                'province' => $AM->User->province,
                'city' => $AM->User->city,
                'country_id' => $AM->User->country_id,
                'country_code' => $AM->User->country_code,
                'website' => $AM->User->website,
                'account' => $Account2Arr,
                'AccountRule'=>$AccountRuleArr,
                'AccountType'=>$AccountTypeArr,
                'Currency'=>$CurrencyArr
            );
        }

        return $AccountMasterArr;
    }

    public function getAccountMaster(){
        return AccountMaster::select('account_master_id as value','merchant_id as text')->get();
    }

    public function getAccountBaseMerchant($id){
        $Accounts = Account::where('account_master_id',$id)->OrderBy('account_id','desc')->get();
        $data = array();
        foreach($Accounts as $Account){
            $data[] = array(
                'account_id'=>$Account->account_id,
                'account_type'=>$Account->AccountType->name,
                'account_rule'=>$Account->AccountRule->name,
                'merchant_id'=>$Account->AccountMaster->merchant_id,
                'transaction_rule'=>$Account->TransactionRule->transaction_rule_name,
                'account_code'=>$Account->account_code,
                'account_no'=>$Account->account_no,
                'deposit'=>$Account->deposit,
                'credit_amount'=>$Account->credit_amount,
                'currency'=>$Account->Currency->title,
                'is_default'=>$Account->is_default,
                'is_active'=>$Account->is_active,
                'status'=>$Account->status,
                'created_by'=>$Account->created_by,
                'modified_by'=>$Account->modified_by,
                'date_added'=>$Account->date_added
            );
        }
        return response()->json(['success'=>true,'data'=>$data,'message'=>'Success.','total'=>count($Accounts)]);
    }

    public function updateAccountBaseMerchant(Request $request,$id)
    {
        $data=(new Account)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(Account::class,$data,'account_id',$id);
    }

    public function getAccountDetail($id){
        $Account='';
        $AccountCredit=[];
        $AccountSendMoney=[];
        $AccountTransfer=[];
        $AccountLoan=[];
        $AccountWithdraw=[];
        $AccountTransaction=[];

        if(Account::where('account_id',$id)->first()){
            $Account = Account::where('account_id',$id)->first();            
        }
        if(AccountCredit::where('account_id',$id)->get()){
            $AccountCredit = AccountCredit::getAccountCreditData($id);
        }
        if(SendMoney::where('account_id',$id)->get()){
            $AccountSendMoney = SendMoney::where('account_id',$id)->get();   
        }
        if(TransferMoney::where('transfer_from_account_no',Account::fetchAccountById($id,'account_no'))->get()){
            $AccountTransfer = TransferMoney::where('transfer_from_account_no',Account::fetchAccountById($id,'account_no'))->get();   
        }
        if(AccountLoan::where('account_id',$id)->get()){
            // $AccountLoan = AccountLoan::where('account_id',$id)->get();
            $AccountLoan = AccountLoan::getLoanData($id);
        }
        if(AccountWithdraw::where('account_id',$id)->get()){
            $AccountWithdraw = AccountWithdraw::where('account_id',$id)->get();   
        }
        if(AccountTransaction::where('account_no',Account::fetchAccountById($id,'account_no'))->OrderBy('transaction_date','desc')->get()){
            $AccountTransaction = AccountTransaction::where('account_no',Account::fetchAccountById($id,'account_no'))->OrderBy('transaction_date','desc')->get();
        }

        $data = array(
            'account_info'=>$Account,
            'account_credit'=>$AccountCredit,
            'account_send_money'=>$AccountSendMoney,
            'account_transfer'=>$AccountTransfer,
            'account_loan'=>$AccountLoan,
            'account_withdraw'=>$AccountWithdraw,
            'account_transaction'=>$AccountTransaction
        );
        return response()->json(['success'=>true,'data'=>$data,'message'=>'Success.']);
    }

    public function saveAccount(Request $request){
        $input = $request->all();
        if(Account::checkHasCurrency($input['account_master_id'],$input['currency_id'])){
            return response()->json(['success'=>false,'message'=>'Account currency has already existed! Please, try another currency!']);
        }else{
            $Account = Account::create(
                [
                    'account_type_id' => $input['account_type_id'],
                    'account_master_id' => $input['account_master_id'],
                    'transaction_rule_id' => 1,
                    'account_code' => $input['account_code'],
                    'account_no' => $input['account_no'],
                    'currency_id' => $input['currency_id'],
                    'is_default' => 0,
                    'is_active' => 0,
                    'created_by' => Auth::user()->id,
                    'modified_by' => Auth::user()->id,
                    'date_added' => date('Y-m-d'),
                    'date_modified' => date('Y-m-d')
                ]
            );
            $AccountCredit = AccountCredit::create(
                [
                    'account_id' => $Account->account_id,
                    'status'=>0,
                    'is_active'=>0
                ]
            );
            return response()->json(['success'=>true,'data'=>$input,'message'=>'Account has been created.']);
        }
    }

    public function generateMerchantID(){
        $generateMerchantID = common::generateMerchantID();
        return response()->json(['success'=>true,'message'=>'Success','data'=>$generateMerchantID],200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $data=$request['data'];
        $input['user_group_id'] = 6;
        // $data["name"]=$input['name'];
        $condition=[
            //'key'=>$data['key']
        ];
        if($data['password']==''){
            $password = $input['password_hidden'];
        }else{
            $password = bcrypt($input['password']);
        }
        $user = User::create([
            'user_group_id'=>$input['user_group_id'],
            'first_name'=>$input['first_name'],
            'last_name'=>$input['last_name'],
            'gender'=>null,
            'dob'=>$input['dob'],
            'company'=>$input['company'],
            'website'=>$input['website'],
            'address_1'=>$input['address_1'],
            'address_2'=>$input['address_2'],
            'username'=>$input['username'],
            'code'=>'+'.$input['country_code'],
            'email'=>$input['email'],
            'password'=>$input['password'],
            'telephone_1'=>$input['telephone_1'],
            'telephone_2'=>$input['telephone_2'],
            'country'=>$input['country'],
            'country_id'=>$input['country_id'],
            'country_code'=>$input['country_code'],
            'province'=>$input['province'],
            'city'=>$input['city'],
            'image'=>null,
            'status'=>1
        ]);
        // $data['user_id'] = $user->id;
        if($user){
            // return (new DataAction)->StoreData(AccountMaster::class,$condition,'',$data);
            AccountMaster::create([
                'user_id' => $user->id,
                'expired_account' => $input['expired_account'],
                'merchant_name' => $input['merchant_name'],
                'merchant_id' => $input['merchant_id'],
                'is_active' => 1,
                'created_by' => Auth::user()->id,
                'modified_by' => Auth::user()->id,
                'date_added' => date('Y-m-d'),
                'date_modified' => date('Y-m-d')
            ]);
            return response()->json(['success'=>true,'message'=>'Account has been created successfully.']);
        }else{
            return response()->json(['success'=>false,'message'=>'Wrong wile trying to register account.']);
        }
    }
    public function show($id)
    {
        $AccountMasterArr = $this->getData($id);
        return response()->json(['success'=>true,'data'=>$AccountMasterArr]);
    }
    public function edit($id)
    {
        $AccountMasterArr = $this->getData($id);
        return response()->json(['success'=>true,'data'=>$AccountMasterArr]);
    }
    public function update(Request $request,$id)
    {
        $input = $request->all();
        $dataUser=(new User)->getFillable();
        $dataUser=$request->only($dataUser);
        // $dataUser['username'] = $input['username'];
        if($dataUser['password']==null){
            $dataUser['password'] = $input['password_hidden'];
        }else{
            $dataUser['password'] = bcrypt($input['password']);
        }
        User::where('id',$input['user_id'])->update($dataUser);
        $data=(new AccountMaster)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(AccountMaster::class,$data,'account_master_id',$id);
        // return response()->json(['success'=>true,'data'=>$request->all(),'message'=>'Account Master successfully updated.']);
    }
    public function destroy($id)
    {
        
    }
}

