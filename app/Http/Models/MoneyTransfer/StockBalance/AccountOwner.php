<?php

namespace App\Http\Models\MoneyTransfer\StockBalance;

use Illuminate\Database\Eloquent\Model;

class AccountOwner extends Model
{
    protected $table='account_owner';
    protected $primaryKey='account_owner_id';
    protected $fillable=[
        "bank_account_id",
        "name",
        "available_credit",
        "currency_id",
        "remark",
        "stock_status_id",
        "is_active"
    ];
    public $timestamps=true;
    
    public function Currency()
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SetupMaster\Currency', 'currency_id');
    }

    public function BankAccount() 
    {
      return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function StockStatus() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SetupMaster\StockStatus', 'stock_status_id');
    }

    static function fetchAccountById($account_owner_id,$field)
    { 
      $AccountOwner = AccountOwner::where("account_owner_id",$account_owner_id)->first();
      return $AccountOwner->$field;
    }
}
