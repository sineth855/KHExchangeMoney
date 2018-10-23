<?php

namespace App\Http\Models\MoneyTransfer\Voucher;

use Illuminate\Database\Eloquent\Model;

class BuyVoip extends Model
{
    protected $table='buy_voip';
    protected $primaryKey='buy_voip_id';
    protected $fillable=[
        'account_no',
        'category_id',
        'product_id',
        'voip_account_no',
        'quantity',
        'currency',
        'currency_id',
        'status',
        'date_added',
        'date_modified'
    ];
    public $timestamps=false;
}
