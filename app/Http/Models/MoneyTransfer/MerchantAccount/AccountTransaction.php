<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    protected $table='account_transaction';
    protected $primaryKey='account_transaction_id';
    protected $fillable=[
        'account_master_id',
        'account_no',
        'transaction_type',
        'transaction_rule',
        'transaction_no',
        'currency',
        'req_amount',
        'transaction_detail',
        'transaction_date'
    ];
    public $timestamps=false;
}
