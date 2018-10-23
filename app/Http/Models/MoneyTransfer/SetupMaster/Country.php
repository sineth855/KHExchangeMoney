<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table='country';
    protected $primaryKey='country_id';
    protected $fillable=[
        'name',
        'iso_code_2',
        'iso_code_3',
        'country_code',
        'image',
        'address_format',
        'postcode_required',
        'status'
    ];
    public $timestamps=false;
}
