<?php

namespace App\Http\Models\MoneyTransfer\Currency;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MoneyTransfer\Currency\ExchangeRate;

class Currency extends Model
{
    protected $table='currency';
    protected $primaryKey='currency_id';
    protected $fillable=[
    	'title',
    	'code',
    	'symbol_left',
    	'symbol_right',
    	'decimal_place',
    	'value',
    	'status',
    	'date_modified'
	];
	
	static function GetExchangeRate($currency_id){
		$sql = ExchangeRate::select('buy_in','sell_out')->where('to_currency_id',$currency_id)->first();
		return $sql;
	}

	static function GetCurrencyBaseId($currency_id,$field){
		$sql = Currency::select($field)->where('currency_id',$currency_id)->first();
		return $sql->$field;
	}

	static function GetCurrencyBaseCode($currency_code,$field){
		$sql = Currency::select($field)->where('code',$currency_code)->first();
		return $sql->$field;
	}
	
    public $timestamps=false;
}
