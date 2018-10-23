<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class AccountWithdraw extends Model
{
    protected $table='account_withdraw';
    // protected $primaryKey='account_credit_id';
    protected $fillable=[
        'account_id',
        'currency',
        'request_amount',
        'requested_date',
        'approved_date',
        'modified_date',
        'is_approved',
        'withdraw_by',
        'status',
        'remark'
    ];
    public $timestamps=false;
}
