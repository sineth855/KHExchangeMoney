<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\LoanProduct;
use App\Http\Models\MoneyTransfer\SetupMaster\TermDay;
use App\Http\Models\MoneyTransfer\SetupMaster\PaymentCycle;
use App\Http\Models\MoneyTransfer\MerchantAccount\InterestMethod;
use Carbon\Carbon;
use App\User;

class AccountLoan extends Model
{
    protected $table='account_loan';
    protected $primaryKey='account_loan_id';
    protected $fillable=[
        'account_id',
        'loan_product_id',
        'principle_amount',
        'loan_duration',
        'term_day_id',
        'payment_cycle_id',
        'expected_disbursement_date',
        'interest_method_id',
        'currency',
        'exchange_rate',
        'loan_interest',
        'tax_amount',
        'total_balance',
        'grace_interest_charge',
        'loan_file',
        'loan_date',
        'approved_date',
        'request_date',
        'is_approved',
        'loan_by',
        'status',
        'remark',
        'created_at',
        'updated_at'
    ];
    public $timestamps=true;

    public function Account()
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    public function LoanProduct()
    {
        return $this->belongsTo(LoanProduct::class,'loan_product_id');
    }
    public function TermDay()
    {
        return $this->belongsTo(TermDay::class,'term_day_id');
    }
    public function PaymentCycle()
    {
        return $this->belongsTo(PaymentCycle::class,'payment_cycle_id');
    }
    public function InterestMethod()
    {
        return $this->belongsTo(InterestMethod::class,'interest_method_id');
    }

    public function Currency() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\Currency\Currency', 'currency_id');
    }

    static function getLoanData($account_id)
    { 
        $AccountLoans = AccountLoan::where('account_id',$account_id)->get();
        $data = [];
        foreach($AccountLoans as $row){
            $data[] = array(
                'account_loan_id'=>$row->account_loan_id,
                'account_id'=>$row->account_id,
                'loan_product_id'=>$row->loan_product_id,
                'loan_product'=>$row->LoanProduct->name,
                'principle_amount'=>$row->principle_amount,
                'loan_duration'=>$row->loan_duration,
                'term_day'=>$row->TermDay->name,
                'term_day_id'=>$row->term_day_id,
                'payment_cycle_id'=>$row->payment_cycle_id,
                'expected_disbursement_date'=>$row->expected_disbursement_date,
                'interest_method_id'=>$row->interest_method_id,
                'currency'=>$row->currency,
                'exchange_rate'=>$row->exchange_rate,
                'loan_interest'=>$row->loan_interest,
                'tax_amount'=>$row->tax_amount,
                'total_balance'=>$row->total_balance,
                'grace_interest_charge'=>$row->grace_interest_charge,
                'loan_file'=>$row->loan_file,
                'loan_date'=>$row->loan_date,
                'approved_date'=>$row->approved_date,
                'request_date'=>$row->request_date,
                'is_approved'=>$row->is_approved,
                'loan_by'=>$row->loan_by,
                'status'=>$row->status,
                'remark'=>$row->remark,
                'created_at'=>$row->created_at,
                'updated_at'=>$row->updated_at
            );
        }
        return $data;
        //   $AccountMaster = AccountMaster::where('account_master_id',$account_master_id)->first();
    }
}
