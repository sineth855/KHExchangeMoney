<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class InterestRatePeriod extends Model
{
    protected $table='interest_rate_period';
    protected $primaryKey='interest_rate_period_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
