<?php

namespace App\Http\Controllers\MoneyTransfer\SendMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SendMoney\SendMoney;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use App\Http\Models\MoneyTransfer\Currency\Currency;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
use App\Helpers\common;
use Auth;

class SendMoneyController extends Controller
{
    public function index()
    {
        $SendMoneyArr = $this->getData($account_id='');
        return response()->json(['success'=>true,'data'=>$SendMoneyArr,'total'=>count($SendMoneyArr)]);
    }

    public function getData($account_id=''){
        if($account_id==''){
            $SendMoney=SendMoney::OrderBy('account_send_money_id','desc')->get();
        }else{
            $SendMoney=SendMoney::OrderBy('account_send_money_id','desc')->where('account_id',$account_id)->first();
        }

        $SendMoneyArr = array();
        $i = 1;
        foreach ($SendMoney as $SM) {
            $SendMoneyArr[] = array(
                'id' => $i,
                'account_no' => $SM->Account->account_no,
                // 'merchant_name' => AccountMaster::getMerchant($SM->Account->account_master_id,"merchant_name"),
                'delivery_method' => $SM->DeliveryMethod->name,
                'receiving_contact' => $SM->receiving_contact,
                'receiving_name' => $SM->receiving_name,
                'sending_currency' => $SM->sending_currency,
                'sending_amount' => $SM->sending_amount,
                'receiving_currency' => $SM->receiving_currency,
                'receiving_amount' => $SM->receiving_amount,
                'sending_amount' => $SM->send_to_merchant_account_no,
                'receiving_date' => $SM->receiving_date,
                'is_received' => $SM->is_received,
                'status' => $SM->status
            );
            $i++;
        }

        return $SendMoneyArr;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        if(Account::checkHasAccount($input['account_id'])){
            $account_no = Account::fetchAccountById($input['account_id'],'account_no');
            if(common::calculateSaveAccount($account_no,$input['sending_amount'])){
                if(Account::checkTransactionAccount($input['account_id'],$input['sending_amount'])){
                    // if(Account::getCurrencyByAccountId($input['account_id'],'code') === $input['receiving_currency']){
                    $input['receiving_amount'] = common::CalculateExchangeRate($input['sending_amount'],Account::getCurrencyByAccountId($input['account_id'],'currency_id'),Currency::GetCurrencyBaseCode($input['receiving_currency'],'currency_id'));
                    $input['sending_currency'] = Account::getCurrencyByAccountId($input['account_id'],'code');
                    $input['exchange_rate'] = Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountId($input['account_id'],'currency_id'));
                    $input['sending_amount'] = $input['sending_amount'];
                    $input['charge_fee'] = common::getAmountConvertCurrency($input['charge_fee'],Account::getCurrencyByAccountId($input['account_id'],'currency_id'),config_currency);
                    // will send to merchant account no as wing or account bank
                    // $input['send_to_merchant_account_no'] = $input['send_to_merchant_account_no'];
                    // Do Transaction By Account
                    $message = "Send Money";
                    $transaction_type = 'Send Money';
                    $sending_amount = 0;
                    if($input['is_received']==true){
                        $sending_amount = $input['sending_amount'];
                        $input['status'] = true;
                    }
                    common::DoTransactionByAccount(Account::fetchAccountById($input['account_id'],'account_master_id'),$account_no,$sending_amount,$message,$transaction_type);
                    // send money
                    $SendMoney = SendMoney::create($input);
                    $data['success'] = true;
                    $data['message'] = "Send Money Successfully.";
                    $data['data'] = $SendMoney;
                    return response()->json($data);
                }else{
                    $data['success'] = false;
                    $data['message'] = "Send money failed, You reached to maximum transaction rule!";
                    return response()->json($data); 
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Send money failed, Insuficient balance!";
                return response()->json($data);
            }
        }else{
            $data['success'] = false;
            $data['message'] = "Failed to send money, no such account!";
            return response()->json($data); 
        }
    }
    public function show($id)
    {
        $SendMoneyArr = $this->getData($id);
        return response()->json($SendMoneyArr);
    }
    public function edit($id)
    {
        return (new DataAction)->EditData(SendMoney::class,$id);
    }
    public function update(Request $request,$id)
    {
        $input = $request->all();
        $data=(new SendMoney)->getFillable();
        $data=$request->only($data);
        if(Account::checkHasAccount($input['account_id'])){
            $account_no = Account::fetchAccountById($input['account_id'],'account_no');
            if(common::calculateSaveAccount($account_no,$input['sending_amount'])){
                if(Account::checkTransactionAccount($input['account_id'],$input['sending_amount'])){
                    $data['receiving_amount'] = common::CalculateExchangeRate($input['sending_amount'],Account::getCurrencyByAccountId($input['account_id'],'currency_id'),Currency::GetCurrencyBaseCode($input['receiving_currency'],'currency_id'));
                    $data['sending_currency'] = Account::getCurrencyByAccountId($input['account_id'],'code');
                    $data['exchange_rate'] = Account::getExchangeRateByCurrencyId(Account::getCurrencyByAccountId($input['account_id'],'currency_id'));
                    $input['charge_fee'] = common::getAmountConvertCurrency($input['charge_fee'],Account::getCurrencyByAccountId($input['account_id'],'currency_id'),config_currency);
                    // will send to merchant account no as wing or account bank
                    // $input['send_to_merchant_account_no'] = $input['send_to_merchant_account_no'];
                    // Do Transaction By Account
                    $message = "Update Send Money Info";
                    $transaction_type = 'Send Money';
                    $sending_amount = 0;
                    if($input['is_received']==true){
                        $sending_amount = $input['sending_amount'];
                        $data['status'] = true;
                    }
                    common::DoTransactionByAccount(Account::fetchAccountById($input['account_id'],'account_master_id'),$account_no,$sending_amount,$message,$transaction_type);
                    return (new DataAction)->UpdateData(SendMoney::class,$data,'account_send_money_id',$id);
                }else{
                    $data['success'] = false;
                    $data['message'] = "Send money updated failed, You reached to maximum transaction rule!";
                    return response()->json($data); 
                }
            }else{
                $data['success'] = false;
                $data['message'] = "Send money failed, Insuficient balance!";
                return response()->json($data);
            }
        }else{
            $data['success'] = false;
            $data['message'] = "Failed to update send money!";
            return response()->json($data); 
        }
    } 
    public function destroy($id)
    {
        
    }
}
