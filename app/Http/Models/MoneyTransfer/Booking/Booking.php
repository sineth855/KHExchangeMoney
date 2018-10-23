<?php

namespace App\Http\Models\MoneyTransfer\Booking;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table='booking';
    protected $primaryKey='booking_id';
    protected $fillable=[
        'account_master_id',
        'account_id',
        'category_id',
        'product_id',
    	'booking_type',
        'quantity',
        'origin_country',
        'destination_country', 
        'origin',
    	'destination',
    	'sector',
    	'booking_date',
    	'fly_date',
    	'fly_time',
        'return_date',
        'return_time',
        'price',
        'exchange_rate',
        'currency',
        'tax_amount',
        'charge_fee',
        'total_price',
        'is_approved',
        'status',
        'date_added',
        'date_modified'
    ];
    public $timestamps=false;

    public function Accounts() 
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    
}
