<?php

namespace App\Http\Models\MoneyTransfer\MerchantAccount;

use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use App\Http\Models\MoneyTransfer\MerchantAccount\TransactionRule;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountRule;
use App\Http\Models\MoneyTransfer\Currency\Currency;
use App\Http\Models\MoneyTransfer\Currency\ExchangeRate;
use Illuminate\Database\Eloquent\Model;
use DB;

class Account extends Model
{
    protected $table='account';
    protected $primaryKey='account_id';
    protected $fillable=[
    	'account_type_id',
    	'account_master_id',
    	'transaction_rule_id',
    	'account_code',
      'account_no',
      'deposit',
      'credit_amount',
    	'currency_id',
    	'is_default',
      'is_active',
      'status',
      'order_level',
      'created_by',
      'modified_by',
      'date_added',
      'date_modified'
    ];
    public $timestamps=false;

    public function TransactionRule() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\MerchantAccount\TransactionRule', 'transaction_rule_id');
    }

    public function AccountType() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\MerchantAccount\AccountType', 'account_type_id');
    }

    public function AccountRule() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\MerchantAccount\AccountRule', 'account_rule_id');
    }

    public function AccountMaster() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster', 'account_master_id');
    }

    public function Currency() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\Currency\Currency', 'currency_id');
    }

    public function SendMoney() 
    {
      return $this->belongsTo('App\Http\Models\MoneyTransfer\SendMoney\SendMoney', 'account_id');
    } 
    
    static function fetchAccount($account_no,$field)
    { 
      $Account = Account::where("account_no",$account_no)->first();
      return $Account->$field;
    }

    static function fetchAccountById($account_id,$field)
    { 
      $Account = Account::where("account_id",$account_id)->first();
      return $Account->$field;
    }

    static function checkAccount($transfer_from_account_no,$transfer_to_account_no)
    { 
      if(Account::checkCurrencyByAccount($transfer_from_account_no)!=false && Account::checkCurrencyByAccount($transfer_to_account_no)!=false){
        if(Account::checkCurrencyByAccount($transfer_from_account_no)==Account::checkCurrencyByAccount($transfer_to_account_no)){
          $boolen = true;
        }else{
          $boolen = false;
        }
      }else{
        $boolen = false;
      }
      return $boolen;
    }

    static function checkRuleAccount($account_id,$amount)
    { 
      $boolen = false;
      $Account = Account::Where('account_id',$account_id)->first();
      $AccountRule = AccountRule::Where('account_rule_id',$Account->account_rule_id)->first();
      if($AccountRule){
        if($AccountRule->min_amount_allowed < $amount){
          $boolen = false;
        }else{
          if($AccountRule->max_amount_allowed >= $amount){
            $boolen = true;
          }else{
            $boolen = false;
          }
        }
      }else{
        $boolen = false;
      }
      return $boolen;
    }

    static function checkTransactionAccount($account_id,$amount)
    { 
      $boolen = false;
      $Account = Account::Where('account_id',$account_id)->first();
      $TransactionRule = TransactionRule::Where('transaction_rule_id',$Account->transaction_rule_id)->first();
      if($TransactionRule){
        if($TransactionRule->amount_limit >= $amount){
          $boolen = true;
        }else{
          $boolen = false;
        }
      }else{
        $boolen = false;
      }
      return $boolen;
    }

    static function checkHasAccountNo($account_no)
    { 
      $query = Account::where('account_no',$account_no)->first();
      $boolen = true;
      if($query){
        $boolen = true;
      }else{
        $boolen = false;
      }
      return $boolen;
    }

    static function checkHasAccount($account_id)
    { 
      $query = Account::where('account_id',$account_id)->first();
      $boolen = true;
      if($query){
        $boolen = true;
      }else{
        $boolen = false;
      }
      return $boolen;
    }

    static function checkHasCurrency($account_master_id,$currency_id)
    { 
      $query = Account::where('account_master_id',$account_master_id)->where('currency_id',$currency_id)->first();
      $boolen = true;
      if($query){
        $boolen = true;
      }else{
        $boolen = false;
      }
      return $boolen;
    }

    static function checkCurrencyByAccount($account_no)
    {
      $Account = Account::select('currency_id')->where('account_no',$account_no)->first();
      if($Account){
        return $Account->currency_id;
      }else{
        return false;
      }
    }

    static function getCurrencyByAccountId($account_id,$field_request)
    {
      $Account = Account::where('account_id',$account_id)->first();
      return Currency::GetCurrencyBaseId($Account->currency_id,$field_request);
    }

    static function getCurrencyByAccountNo($account_no,$field_request)
    {
      $Account = Account::where('account_no',$account_no)->first();
      return Currency::GetCurrencyBaseId($Account->currency_id,$field_request);
    }

    static function getExchangeRateByCurrencyId($currency_id)
    {
      $ExchangeRate = ExchangeRate::where('to_currency_id',$currency_id)->first();
      return $ExchangeRate->rate;
    }
    
    static function GetAccountMaster($account_master_id)
    {
      $AccountMaster = AccountMaster::Where('account_master_id',$account_master_id)->get();
      return $AccountMaster;
    }

    static function GetAccountTransferee($account_transferee_no)
    {
      $AccountTransferee = Account::Where('account_no',$account_transferee_no)->first();
      $getAccountMasterTransferee = AccountMaster::where('account_master_id',$AccountTransferee->account_master_id)->first();
      $data = array(
        'merchant_name'=>$getAccountMasterTransferee->merchant_name,
        'account_no'=>$AccountTransferee->account_no
      );
      return $data;
    }
}
