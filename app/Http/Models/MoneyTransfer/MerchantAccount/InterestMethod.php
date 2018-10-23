<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class InterestMethod extends Model
{
    protected $table='interest_method';
    protected $primaryKey='interest_method_id';
    protected $fillable=[
        'interest_method_id',
        'name',
        'minimun_cash',
        'maxinum_cash',
        'fix_charge',
        'percentage',
        'processing_period',
        'term_day_id'
    ];
    public $timestamps=false;
}
