<?php

    namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountLoan;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Models\MoneyTransfer\SetupMaster\InterestMethod;
    use App\Http\Controllers\MoneyTransfer\commons\DataAction;
    use Validator;
    use Carbon\Carbon;
    use App\Helpers\common;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;

    class AccountLoanController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function index(){
            $AccountLoans = AccountLoan::all();
            $data = array();
            $i=1;
            foreach($AccountLoans as $al){
                $data[] = array(
                    'id' => $i,
                    'account_loan_id' => $al->account_loan_id,
                    'account_no' => $al->Account->account_no,
                    'loan_product' => $al->LoanProduct->name,
                    'principle_amount' => $al->principle_amount,
                    'loan_duration' => $al->loan_duration.' '.$al->TermDay->name,
                    'term_day' => $al->TermDay->name,
                    'payment_cycle' => $al->PaymentCycle->name,
                    'expected_disbursement_date' => $al->expected_disbursement_date,
                    'currency' => $al->currency,
                    'exchange_rate' => $al->exchange_rate,
                    'interest_method' => $al->InterestMethod->name,
                    'loan_interest' => $al->loan_interest,
                    'grace_interest_charge' => $al->grace_interest_charge,
                    'loan_file' => $al->loan_file,
                    'loan_date' => $al->loan_date,
                    'approved_date' => $al->approved_date,
                    'request_date' => $al->request_date,
                    'is_approved' => $al->is_approved,
                    'status' => $al->status,
                    'created_at' => $al->created_at,
                    'updated_at'=> $al->updated_at
                );
                $i++;
            }
            return response()->json(['success'=>true,'message'=>'Success','data'=>$data,'total'=>count($data)], 200);
        }

        public function store(Request $request)
        {
            $input = $request->all();
            $data=(new AccountLoan)->getFillable();
            $data=$request->only($data);
            $accountCurrency = Account::fetchAccountById($input['account_id'],'currency_id');
            $data['tax_amount'] = config_tax;
            $loan_interest = $this->getInterestAmount($data['principle_amount'], $accountCurrency);
            $data['loan_interest'] = $loan_interest;
            $data['loan_by'] = Auth::user()->id;
            $Account = Account::where('account_id',$input['account_id'])->first();
            $data['currency'] = $Account->Currency->title;
            $data['exchange_rate'] = $Account->Currency->value;
            $data['request_date'] = date("Y-m-d");
            $condition=[
                //'key'=>$data['key']
            ];
            $message = 'Loan amount '.$data['principle_amount'];
            $transaction_type = 'Loan Account';
            $principle_amount = 0;
            if($request->is_approved==true){
                if(Account::checkRuleAccount($request->account_id,$data['principle_amount'])){
                    $principle_amount = $data['principle_amount'];
                    $data['total_balance'] =  $data['principle_amount'] + config_tax + $loan_interest;
                    common::DoCreditAccountBaseCurrency($data['account_id'],$principle_amount,$message,$transaction_type);
                    return (new DataAction)->StoreData(AccountLoan::class,$condition,'',$data);
                }else{
                    return response()->json(['success'=>false,'message'=>'This amount request is not permitted for process loan!']);
                }
            }else{
                common::DoCreditAccountBaseCurrency($data['account_id'],$principle_amount,$message,$transaction_type);
                return (new DataAction)->StoreData(AccountLoan::class,$condition,'',$data);
            }
        }
        public function edit($id)
        {
            return (new DataAction)->EditData(AccountLoan::class,$id);
        }
        public function update(Request $request,$id)
        {
            $data=(new AccountLoan)->getFillable();
            $data=$request->only($data);
            $accountCurrency = Account::fetchAccountById($input['account_id'],'currency_id');
            $data['tax_amount'] = config_tax;
            $loan_interest = $this->getInterestAmount($data['principle_amount'], $accountCurrency);
            $data['loan_interest'] = $loan_interest;
            $data['loan_by'] = Auth::user()->id;
            $Account = Account::where('account_id',$input['account_id'])->first();
            $data['currency'] = $Account->Currency->title;
            $data['exchange_rate'] = $Account->Currency->value;
            $data['request_date'] = date("Y-m-d");

            if(Account::checkRuleAccount($request->account_id,$data['principle_amount'])){
                $message = 'Update Loan amount '.$data['principle_amount'];
                $transaction_type = 'Loan Account';
                $principle_amount = 0;
                if($request->is_approved==true){
                    $principle_amount = floatval($data['principle_amount']) + floatval($loan_interest);
                    $data['total_balance'] =  $data['principle_amount'] + config_tax + $loan_interest;
                }
                common::DoCreditAccountBaseCurrency($data['account_id'],$principle_amount,$message,$transaction_type);
                return (new DataAction)->UpdateData(AccountLoan::class,$data,'account_loan_id',$id);
            }else{
                return response()->json(['success'=>false,'message'=>'This amount request is not permitted for process loan!']);
            }
        }

        public function getInterestAmount($amount, $account_currency){
            $InterestMethod = InterestMethod::where('currency_id',$account_currency)
                                              ->where('maxinum_cash','>=',$amount)
                                              ->first();

            $charge_amount = 0;
            if($InterestMethod){
                $charge_amount = $InterestMethod->fix_charge;
            }else{
                $charge_amount = 0;
            }
            return $charge_amount;
        }
    }