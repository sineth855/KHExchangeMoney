<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class InterestMethod extends Model
{
    protected $table='interest_method';
    protected $primaryKey='interest_method_id';
    protected $fillable=[
        "name",
        "minimun_cash",
        "maxinum_cash",
        "fix_charge",
        "currency_id",
        "percentage",
        "processing_period",
        "term_day_id"
    ];
    public $timestamps=false;

    public function TermDay() 
    {
      return $this->belongsTo(TermDay::class, 'term_day_id');
    }

    public function Currency() 
    {
      return $this->belongsTo(Currency::class, 'currency_id');
    }
}
