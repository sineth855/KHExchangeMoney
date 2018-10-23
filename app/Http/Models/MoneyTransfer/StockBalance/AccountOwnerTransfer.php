<?php

namespace App\Http\Models\MoneyTransfer\StockBalance;

use Illuminate\Database\Eloquent\Model;

class AccountOwnerTransfer extends Model
{
    protected $table='account_owner_transfer';
    protected $primaryKey='account_owner_transfer_id';
    protected $fillable=[
        "transfer_no",
        "transfer_from_account_no",
        "transfer_to_account_customer_no",
        "delivery_method",
        "exchange_rate",
        "transfer_from_currency",
        "transfer_to_currency",
        "transfer_amount",
        "receiving_amount",
        "reference_no",
        "transfer_date",
        "transfer_fee",
        "status",
        "created_by",
        "tax",
        "remark"
    ];
    public $timestamps=false;
}
