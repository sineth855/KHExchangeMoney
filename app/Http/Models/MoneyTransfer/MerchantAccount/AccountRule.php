<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class AccountRule extends Model
{
    protected $table='account_rule';
    protected $primaryKey='account_rule_id';
    protected $fillable=[
        'account_rule_id',
        'currency_id',
        'name',
        'min_amount_allowed',
        'max_amount_allowed',
        'remark'
    ];
    public $timestamps=false;
}
