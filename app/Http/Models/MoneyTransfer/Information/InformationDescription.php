<?php

namespace App\Http\Models\BackEnd\Information;

use Illuminate\Database\Eloquent\Model;

class InformationDescription extends Model
{
    protected $table='information_description';
	protected $primaryKey='information_id';
	protected $fillable=[
		'information_id',
		'language_id',
		'name'
	];
	public $timestamps=false;
}
