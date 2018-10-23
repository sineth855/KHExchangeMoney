<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table='account_type';
    protected $primaryKey='account_type_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
