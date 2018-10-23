<?php

namespace App\Http\Models\MoneyTransfer\StockBalance;

use Illuminate\Database\Eloquent\Model;

class AccountOwnerCredit extends Model
{
    protected $table='account_owner_credit';
    protected $primaryKey='account_owner_id';
    protected $fillable=[
        "credit_amount",
        "is_active",
        "remark"
    ];
    public $timestamps=true;
}
