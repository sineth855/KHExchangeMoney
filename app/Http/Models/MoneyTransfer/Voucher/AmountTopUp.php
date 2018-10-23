<?php

namespace App\Http\Models\MoneyTransfer\Voucher;

use Illuminate\Database\Eloquent\Model;

class AmountTopUp extends Model
{
    protected $table='amount_top_up';
    protected $primaryKey='amount_top_up_id';
    protected $fillable=[
        'country_id',
        'currency_id',
        'voucher_id',
    	'name',
    	'order_level'
    ];
    public $timestamps=false;
}
