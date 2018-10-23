<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $table='loan_product';
    protected $primaryKey='loan_product_id';
    protected $fillable=[
        'loan_product_id',
        'name',
        'principle_default_amount',
        'principle_min_amount',
        'principle_max_amount',
        'term_period',
        'term_day_id',
        'payment_cycle_id',
        'interest_rate_period_id',
        'interest_method_id',
        'account_rule_id'
    ];
    public $timestamps=false;
}
