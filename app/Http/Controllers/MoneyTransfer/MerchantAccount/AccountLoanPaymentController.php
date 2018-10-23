<?php

    namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountLoan;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountLoanPayment;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use App\Http\Controllers\MoneyTransfer\commons\DataAction;
    use Validator;
    use Carbon\Carbon;
    use App\Helpers\common;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;

    class AccountLoanPaymentController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function index()
        {
            $AccountLoanPayments = AccountLoanPayment::OrderBy('account_loan_payment_id','desc')->get();
            $data = array();
            foreach($AccountLoanPayments as $row){
                $data[] = array(
                    'account_loan_payment_id'=>$row->account_loan_payment_id,
                    'account_loan_id'=>$row->account_loan_id,
                    'receipt_no'=>$row->receipt_no,
                    'extra_payment'=>$row->extra_payment,
                    'total_amount'=>$row->total_amount,
                    'tax_amount'=>$row->tax_amount,
                    'amount'=>$row->amount,
                    'payment_date'=>$row->payment_date,
                    'status'=>$row->status,
                    'remark'=>$row->remark
                );
            }
            return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
        }
        public function store(Request $request)
        {
            $data=$request['data'];
            $data=$request->only($data);
            $data['receipt_no'] = common::generateSecureNum();
            $data['total_amount'] = floatval($data['amount']) + floatval($data['extra_payment']);
            $data['payment_date'] = date("Y-m-d");
            $data['status'] = 1;
            $condition=[
                //'key'=>$data['key']
            ];
            $AccountLoanPayment = AccountLoanPayment::calculateLoanPayment($data['account_loan_id'], $data['total_amount'], $data['currency']);
            if($AccountLoanPayment){
                return (new DataAction)->StoreData(AccountLoanPayment::class,$condition,'',$data);
            }else{
                return response()->json(['success'=>false,'message'=>'Process loan payment failed, or balance does not match!']);
            }
        }
        public function show($id)
        {

        }

        public function getLoanPayment($loan_id){
            $AccountLoanPayments = AccountLoanPayment::where('account_loan_id',$loan_id)->get();
            return response()->json(['success'=>true,'data'=>$AccountLoanPayments,'total'=>count($AccountLoanPayments)]);
        }

        public function edit($id)
        {
            return (new DataAction)->EditData(AccountLoanPayment::class,$id);
        }
        public function update(Request $request,$id)
        {
            $data=(new AccountLoanPayment)->getFillable();
            $data=$request->only($data);
            // if(@$data['image']){
            //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
            // }
            return (new DataAction)->UpdateData(AccountLoanPayment::class,$data,'account_loan_payment_id',$id);
        }
        public function destroy($id)
        {
            return (new DataAction)->DeleteData(AccountLoanPayment::class,'account_loan_payment_id',$id);
        }
    }