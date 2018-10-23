<?php

    namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\MoneyTransfer\AccountLoan;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountCredit;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;
    use App\Http\Controllers\MoneyTransfer\commons\DataAction;
    class AccountCreditController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function index(){
            $AccountCredits = AccountCredit::OrderBy('deposit_date','desc')->get();
            $i=1;
            $data = array();
            foreach($AccountCredits as $ac){
                $data[] = array(
                    'account_credit_id' => $ac->account_credit_id,
                    'merchant_account' => $ac->account_id,
                    'account_no' => Account::fetchAccountById($ac->account_id,"account_no"),
                    'credit_amount' => $ac->credit_amount,
                    'credit_amount_history' => $ac->credit_amount_history,
                    'deposit_date' => $ac->deposit_date,
                    'status' => $ac->status,
                    'is_active' => $ac->is_active,
                    'deposit_by' => $ac->deposit_by
                );
                $i++;
            }
            // dd($AccountCredits);
            return response()->json(['success'=>true,'message'=>'Success','data'=>$data,'total'=>count($data)], 200);
        }
        public function store(Request $request)
        {
            // $input = $request->all();
            $data=(new AccountCredit)->getFillable();
            $data=$request->only($data);
            $data['credit_amount_history'] = $data['credit_amount'];
            $data['deposit_date'] = date('Y-m-d H:i:s');
            $data['deposit_by'] = Auth::user()->id;
            $data['status'] = 1;
            $condition=[
                //'key'=>$data['key']
            ];
            $message = 'Credit amount'.$data['credit_amount'];
            $transaction_type = 'Credit Account';
            $credit_amount = 0;
            if($request->is_active==true){
                $credit_amount = $data['credit_amount'];
            }
            common::DoCreditAccountBaseCurrency($data['account_id'],$credit_amount,$message,$transaction_type);
            return (new DataAction)->StoreData(AccountCredit::class,$condition,'',$data);
        }
        public function edit($id)
        {
            return (new DataAction)->EditData(AccountCredit::class,$id);
        }
        public function update(Request $request,$id)
        {
            $data=(new AccountCredit)->getFillable();
            $data=$request->only($data);
            $message = 'Update credit amount'.$data['credit_amount'];
            $transaction_type = 'Credit Account';
            $credit_amount = 0;
            if($request->is_active){
                $credit_amount = $data['credit_amount'];
            }
            common::DoCreditAccountBaseCurrency($data['account_id'],$data['credit_amount'],$message,$transaction_type);

            $data=(new AccountCredit)->getFillable();
            $data=$request->only($data);
            return (new DataAction)->UpdateData(AccountCredit::class,$data,'account_credit_id',$id);
        }
        
    }