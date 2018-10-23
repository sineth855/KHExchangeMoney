<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    protected $table='stock_status';
    protected $primaryKey='stock_status_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
