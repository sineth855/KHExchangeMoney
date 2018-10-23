<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethodType extends Model
{
    protected $table='delivery_method_type';
    protected $primaryKey='delivery_method_type_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
