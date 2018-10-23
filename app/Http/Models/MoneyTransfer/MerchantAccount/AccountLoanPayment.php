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

class AccountLoanPayment extends Model
{
    protected $table='account_loan_payment';
    protected $primaryKey='account_loan_payment_id';
    protected $fillable=[
        'account_loan_payment_id',
        'account_loan_id',
        'receipt_no',
        'extra_payment',
        'total_amount',
        'tax_amount',
        'amount',
        'payment_date',
        'status',
        'remark'
    ];
    public $timestamps=false;

    static function calculateLoanPayment($account_loan_id,$total_amount,$currency)
    { 
        $boolen = false;
        $total_balance = 0;
        $status = 0;
        $AccountLoan = AccountLoan::where('account_loan_id',$account_loan_id)->first();
        if($currency==$AccountLoan->currency){
            if($AccountLoan->status == 1){
                $boolen = false;
            }else{
                if(floatval($AccountLoan->total_balance)>=floatval($total_amount) && floatval($AccountLoan->total_balance)!=0){
                    $total_balance = floatval($AccountLoan->total_balance) - floatval($total_amount);
                    if($total_balance==0){
                        $status = 1;
                    }
                    AccountLoan::where('account_loan_id',$account_loan_id)->update(['total_balance'=>$total_balance,'status'=>$status]);
                    $boolen = true;
                }else{
                    $boolen = false;
                }
            }
        }else{
            $boolen = false;
        }
        return $boolen;
    }
}
