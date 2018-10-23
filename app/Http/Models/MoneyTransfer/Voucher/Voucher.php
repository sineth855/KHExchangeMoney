<?php

namespace App\Http\Models\MoneyTransfer\Voucher;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table='voucher';
    protected $primaryKey='voucher_id';
    protected $fillable=[
    	'country_id',
        'image',
        'name',
        'order_level'
    ];
    public $timestamps=false;
}
