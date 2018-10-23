<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class TransactionChargeFee extends Model
{
    protected $table='transaction_charge_fee';
    protected $primaryKey='transaction_charge_fee_id';
    protected $fillable=[
        'name',
        'condition_amount',
        'charge_fee',
        'remark'
    ];
    public $timestamps=false;
}
