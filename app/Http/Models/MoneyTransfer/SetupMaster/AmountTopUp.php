<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class AmountTopUp extends Model
{
    protected $table='amount_top_up';
    protected $primaryKey='amount_top_up_id';
    protected $fillable=[
        'country_id',
        'currency_id',
        'voucher_id',
        'name',
        'order_level'
    ];
    public $timestamps=false;

    public function Country() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SetupMaster\Country', 'country_id');
    }

    public function Currency()
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SetupMaster\Currency', 'currency_id');
    }

    public function Voucher() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SetupMaster\Voucher', 'voucher_id');
    }
    
}
