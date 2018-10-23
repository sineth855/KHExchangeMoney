<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class BookingType extends Model
{
    protected $table='booking_type';
    protected $primaryKey='booking_type_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
