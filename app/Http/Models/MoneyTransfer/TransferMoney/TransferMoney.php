<?php

namespace App\Http\Models\MoneyTransfer\TransferMoney;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MoneyTransfer\SetupMaster\DeliveryMethod;
use App\Http\Models\MoneyTransfer\SetupMaster\Currency;
use Carbon\Carbon;

class TransferMoney extends Model
{
    protected $table='account_transfer';
    protected $primaryKey='account_transfer_id';
    protected $fillable=[
        'transfer_no',
    	'transfer_from_account_no',
    	'transfer_to_account_no',
    	'delivery_method',
    	'transfer_from_currency_id',
        'transfer_to_currency_id',
        'transfer_amount',
        'receiving_amount',
        'reference_no',
        'transfer_date',
        'transfer_fee',
        'status',
        'created_by',
        'tax',
        'remark'
    ];
    public $timestamps=true;

    public function DeliveryMethod() 
    {
        return $this->belongsTo(DeliveryMethod::class,'delivery_method');
    }

    public function TransferFromCurrency()
    {
        return $this->belongsTo(Currency::class,'transfer_from_currency_id');
    }

    public function TransferToCurrency()
    {
        return $this->belongsTo(Currency::class,'transfer_to_currency_id');
    }
}

