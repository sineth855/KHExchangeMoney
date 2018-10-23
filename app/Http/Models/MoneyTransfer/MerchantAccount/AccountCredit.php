<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;
use App\Http\Models\MoneyTransfer\User\User;
use Illuminate\Database\Eloquent\Model;

class AccountCredit extends Model
{
    protected $table='account_credit';
    protected $primaryKey='account_credit_id';
    protected $fillable=[
        'account_id',
        'deposit_date',
        'credit_amount',
        'credit_amount_history',
        'status',
        'is_active',
        'deposit_by'
    ];
    public $timestamps=false;

    public function User(){
		return $this->belongsTo(User::class,'deposit_by');
    }
    
    static function getAccountCreditData($account_id)
    { 
        $AccountCredits = AccountCredit::where('account_id',$account_id)->get();
        $data = [];
        foreach($AccountCredits as $row){
            $data[] = array(
                'account_credit_id' => $row->account_credit_id,
                'account_id' => $row->account_id,
                'deposit_amount' => $row->deposit_amount,
                'deposit_date' => $row->deposit_date,
                'credit_amount' => $row->credit_amount,
                'credit_amount_history' => $row->credit_amount_history,
                'status' => $row->status,
                'is_active' => $row->is_active,
                'deposit_by' => $row->User->last_name.' '.$row->User->first_name
            );
        }
        return $data;
    }
}
