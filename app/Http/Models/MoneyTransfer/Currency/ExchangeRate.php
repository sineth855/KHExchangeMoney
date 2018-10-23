<?php

namespace App\Http\Models\MoneyTransfer\Currency;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table='exchange_rate';
    protected $primaryKey='exchange_rate_id';
    protected $fillable=[
    	'from_currency_id',
        'to_currency_id',
        'buy_in',
        'sell_out',
    	'rate',
    	'date_added'
    ];
    public $timestamps=false;

    public function ExchangeFromCurrency(){
        return $this->belongsTo(Currency::class,'from_currency_id');
    }

    public function ExchangeToCurrency(){
        return $this->belongsTo(Currency::class,'to_currency_id');
    }
}
