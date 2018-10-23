<?php

    namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\MoneyTransfer\AccountLoan;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountWithdraw;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;
    use App\Http\Controllers\MoneyTransfer\commons\DataAction;
    class AccountWithdrawalController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function index(){
            $AccountWithdraw = AccountWithdraw::OrderBy('requested_date','desc')->get();
            // $i=1;
            // $data = array();
            // foreach($AccountWithdraw as $ac){
            //     $data[] = array(
            //         'account_credit_id' => $ac->account_credit_id,
            //         'merchant_account' => $ac->account_id,
            //         'account_no' => Account::fetchAccountById($ac->account_id,"account_no"),
            //         'credit_amount' => $ac->credit_amount,
            //         'credit_amount_history' => $ac->credit_amount_history,
            //         'deposit_date' => $ac->deposit_date,
            //         'status' => $ac->status,
            //         'is_active' => $ac->is_active,
            //         'deposit_by' => $ac->deposit_by
            //     );
            //     $i++;
            // }
            // dd($AccountWithdraw);
            return response()->json(['success'=>true,'message'=>'Success','data'=>$AccountWithdraw,'total'=>count($AccountWithdraw)], 200);
        }
        public function store(Request $request)
        {
            $input = $request->all();
            $data=(new AccountWithdraw)->getFillable();
            $data=$request->only($data);
            $Account = Account::where('account_id',$input['account_id'])->first();
            $data['currency'] = $Account->Currency->title;
            $data['exchange_rate'] = $Account->Currency->value;
            $data['requested_date'] = date('Y-m-d H:i:s');
            $data['modified_date'] = date('Y-m-d H:i:s');
            $data['withdraw_by'] = Auth::user()->id;
            $condition=[
                //'key'=>$data['key']
            ];
            $message = 'Credit amount'.$data['request_amount'];
            $transaction_type = 'Withdrawal';
            common::DoCreditAccountBaseCurrency($data['account_id'],$data['request_amount'],$message,$transaction_type);
            return (new DataAction)->StoreData(AccountWithdraw::class,$condition,'',$data);
        }
        public function edit($id)
        {
            return (new DataAction)->EditData(AccountWithdraw::class,$id);
        }
        public function update(Request $request,$id)
        {
            $data=(new AccountWithdraw)->getFillable();
            $data=$request->only($data);
            $data['modified_date'] = date('Y-m-d H:i:s');
            return (new DataAction)->UpdateData(AccountWithdraw::class,$data,'account_credit_id',$id);
        }
        
    }