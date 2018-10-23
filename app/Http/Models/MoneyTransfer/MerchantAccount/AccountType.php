<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table='account_type';
    protected $primaryKey='account_type_id';
    protected $fillable=[
        'account_type_id',
    	'name'
    ];
    public $timestamps=false;
}
