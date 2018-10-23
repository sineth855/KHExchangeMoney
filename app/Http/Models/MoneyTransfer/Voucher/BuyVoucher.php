<?php

namespace App\Http\Models\MoneyTransfer\Voucher;

use Illuminate\Database\Eloquent\Model;

class BuyVoucher extends Model
{
    protected $table='buy_voucher';
    protected $primaryKey='buy_voucher_id';
    protected $fillable=[
        'category_id',
        'product_id',
    	'from_account_no',
        'amount_top_up',
        'quantity',
        'currency',
        'country_id',
        'pay_to_voucher',
        'telephone',
    	'transfer_date',
    	'message',
        'status'
    ];
    public $timestamps=false;
}
