<?php

namespace App\Http\Models\MoneyTransfer\StockBalance;

use Illuminate\Database\Eloquent\Model;

class AccountOwnerHistory extends Model
{
    protected $table='account_owner_history';
    protected $primaryKey='account_owner_history_id';
    protected $fillable=[
        "account_owner_history",
        "amount_due",
        "currency",
        "exchange_rate",
        "due_date",
        "remark"
    ];
    public $timestamps=false;
    
}
