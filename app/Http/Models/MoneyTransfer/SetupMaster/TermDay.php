<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class TermDay extends Model
{
    protected $table='term_day';
    protected $primaryKey='term_day_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
