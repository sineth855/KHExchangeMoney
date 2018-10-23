<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class RuleType extends Model
{
    protected $table='rule_type';
    protected $primaryKey='rule_type_id';
    protected $fillable=[
    	'name'
    ];
    public $timestamps=false;
}
