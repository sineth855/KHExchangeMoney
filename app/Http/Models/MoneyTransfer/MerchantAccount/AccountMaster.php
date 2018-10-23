<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use App\Helpers\common;

class AccountMaster extends Model
{
    protected $table='account_master';
    protected $primaryKey='account_master_id';
    protected $fillable=[
        'account_master_id',
        'user_id',
        'deviceId',
    	'finger_print',
        'expired_account',
        'merchant_name',
    	'merchant_id',
        'is_active',
        'created_by',
        'modified_by',
        'date_added',
        'date_modified'
    ];
    public $timestamps=false;

    public function Account()
    {
        return $this->hasMany("App\Http\Models\MoneyTransfer\MerchantAccount\Account",'account_master_id');
    }
    
    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getAccount()
    {
        return $this->hasMany(Account::class,'account_master_id')->get();
    }
    
    static function getMerchant($account_master_id,$field)
    { 
      $AccountMaster = AccountMaster::where('account_master_id',$account_master_id)->first();
      return $AccountMaster->$field;
    }

}
