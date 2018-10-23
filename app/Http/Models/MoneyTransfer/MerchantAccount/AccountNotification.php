<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;

class AccountNotification extends Model
{
    protected $table='account_notification';
    protected $primaryKey='account_notification_id';
    protected $fillable=[
        'notification_type',
        'account_no',
        'notification_title',
        'transaction_amount',
        'currency',
        'transaction_detail',
        'is_read',
        'transaction_date'
    ];
    public $timestamps=false;
}
