<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class TransactionRule extends Model
{
    protected $table='transaction_rules';
    protected $primaryKey='transaction_rule_id';
    protected $fillable=[
        'transaction_rule_name',
        'currency_id',
        'country_id',
        'rule_type_id',
        'restrict_type_id',
        'delivery_method_id',
        'amount_limit',
        'remark'
    ];
    public $timestamps=false;

    public function Currency() 
    {
      return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function Country() 
    {
      return $this->belongsTo(Country::class, 'country_id');
    }

    public function RuleType() 
    {
      return $this->belongsTo(RuleType::class, 'rule_type_id');
    }

    public function RestrictType() 
    {
      return $this->belongsTo(RestrictType::class, 'restrict_type_id');
    }

    public function DeliveryMethod() 
    {
      return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }
}
