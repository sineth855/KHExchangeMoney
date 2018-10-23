<?php

namespace App\Http\Models\MoneyTransfer\Location;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table='location';
    protected $primaryKey='location_id';
    protected $fillable=[
        'name',
        'Latitude',
        'Longitude',
        'address',
        'telephone',
        'fax',
        'geocode',
        'image',
        'open',
        'comment'
    ];
    public $timestamps=false;
}
