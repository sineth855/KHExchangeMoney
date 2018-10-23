<?php

namespace App\Http\Models\MoneyTransfer\StockBalance;

use Illuminate\Database\Eloquent\Model;

class AccountOwnerWithdraw extends Model
{
    protected $table='account_owner_withdraw';
    // protected $primaryKey='account_owner_id';
    protected $fillable=[
        "currency",
        "exchange_rate",
        "request_amount",
        "requested_date",
        "approved_date",
        "modified_date",
        "is_approved",
        "withdraw_by",
        "status",
        "remark"
    ];
    public $timestamps=false;
}
