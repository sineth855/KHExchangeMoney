<?php

namespace App\Http\Models\MoneyTransfer\Currency;

use Illuminate\Database\Eloquent\Model;

class ExchangeRateHistory extends Model
{
    protected $table='exchange_rate_history';
    protected $primaryKey='exchange_rate_history_id';
    protected $fillable=[
        "from_currency",
        "to_currency",
        "buy_in",
        "sell_out",
        "rate",
        "date_added"
    ];
    public $timestamps=false;
}
