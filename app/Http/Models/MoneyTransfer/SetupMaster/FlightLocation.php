<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class FlightLocation extends Model
{
    protected $table='flight_location';
    protected $primaryKey='id';
    protected $fillable=[
        'country_id',
        'name',
        'is_active'
    ];
    public $timestamps=true;

    public function Country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}
