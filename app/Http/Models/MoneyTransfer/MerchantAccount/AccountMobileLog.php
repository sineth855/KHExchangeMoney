<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;

class AccountMobileLog extends Model
{
    protected $table='account_mobile_log';
    protected $primaryKey='account_mobile_log_id';
    protected $fillable=[
        'account_mobile_log_id',
        'merchant_no',
        'token_id',
    	'ip',
    	'expire_date',
        'security_key',
        'status',
        'remark',
        'date_added'
    ];
    public $timestamps=false;
}
