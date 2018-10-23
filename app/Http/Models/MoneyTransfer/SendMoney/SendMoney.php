<?php

namespace App\Http\Models\MoneyTransfer\SendMoney;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SendMoney extends Model
{
    protected $table='account_send_money';
    protected $primaryKey='account_send_money_id';
    protected $fillable=[
        'account_id',
    	'delivery_method_id',
    	'exchange_rate',
    	'sending_currency',
        'sending_amount',
        'charge_fee',
    	'receiving_currency',
    	'receiving_amount',
    	'receiving_contact',
        'receiving_name',
        'send_to_merchant_account_no',
        'receiving_date',
        'is_received',
        'status',
        'remark',
        'created_at',
        'updated_at'
    ];
    public $timestamps=true;

    public function DeliveryMethod() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SetupMaster\DeliveryMethod', 'delivery_method_id');
    }

    public function Account() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\MerchantAccount\Account', 'account_id');
    }
}
