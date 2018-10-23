<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class AccountRule extends Model
{
    protected $table='account_rule';
    protected $primaryKey='account_rule_id';
    protected $fillable=[
        'currency_id',
        'name',
        'min_amount_allowed',
        'max_amount_allowed',
        'remark'
    ];
    public $timestamps=false;

    public function Currency() 
    {
      return $this->belongsTo(Currency::class, 'currency_id');
    }
}
