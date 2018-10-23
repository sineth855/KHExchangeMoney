<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class PaymentCycle extends Model
{
    protected $table='payment_cycle';
    protected $primaryKey='payment_cycle_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
