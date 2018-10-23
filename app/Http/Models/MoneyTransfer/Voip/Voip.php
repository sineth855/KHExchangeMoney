<?php

namespace App\Http\Models\MoneyTransfer\Voip;
use App\Http\Models\MoneyTransfer\SetupMaster\Currency;
use Illuminate\Database\Eloquent\Model;

class Voip extends Model
{
    protected $table='buy_voip';
    protected $primaryKey='buy_voip_id';
    protected $fillable=[
        'account_no',
        'voip_account_no',
        'amount',
        'currency_id',
        'status',
        'date_added',
        'date_modified'
    ];
    public $timestamps=false;

    public function Currency(){
		return $this->belongsTo(Currency::class,'currency_id');
	}
}
