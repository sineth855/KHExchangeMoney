<?php

    namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\MoneyTransfer\AccountLoan;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountCredit;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountTransaction;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;

    class AccountTransactionController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function index(){
            $AccountTransactions = AccountTransaction::OrderBy('transaction_date','desc')->get();
            $i=1;
            $data = array();
            foreach($AccountTransactions as $at){
                $data[] = array(
                    'id' => $i,
                    'account_transaction_id' => $at->account_transaction_id,
                    'account_master_id' => $at->account_master_id,
                    'account_no' => $at->account_no,
                    'transaction_rule' => $at->transaction_rule,
                    'transaction_no' => $at->transaction_no,
                    'currency' => $at->currency,
                    'req_amount' => $at->req_amount,
                    'transaction_detail' => $at->transaction_detail,
                    'transaction_date' => $at->transaction_date,
                );
                $i++;
            }
            // dd($AccountTransactions);
            return response()->json(['success'=>true,'message'=>'Success','data'=>$data], 200);
        }
    }