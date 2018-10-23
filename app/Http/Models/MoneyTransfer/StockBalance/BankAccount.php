<?php

namespace App\Http\Models\MoneyTransfer\StockBalance;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table='bank_account';
    protected $primaryKey='bank_account_id';
    protected $fillable=[
        "bank_name",
        "currency",
        "country",
        "account_no",
        "account_name",
        "expired_account",
        "contact",
        "email",
        "merchant_id",
        "merchant_name"
    ];
    public $timestamps=false;
}
