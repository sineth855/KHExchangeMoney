<?php

namespace App\Http\Models\MoneyTransfer\Language;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table='language';
    protected $primaryKey='language_id';
    protected $fillable=[
    	'name',
    	'code',
    	'locale',
    	'directory',
    	'image',
    	'sort_order',
    	'status'
    ];
    public $timestamps=false;
}
