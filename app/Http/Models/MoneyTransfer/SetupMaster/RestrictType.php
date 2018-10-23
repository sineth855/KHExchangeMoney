<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class RestrictType extends Model
{
    protected $table='restrict_type';
    protected $primaryKey='restrict_type_id';
    protected $fillable=[
    	'name'
    ];
    public $timestamps=false;
}
