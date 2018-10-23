<?php

namespace App\Http\Controllers\MoneyTransfer\TransferMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\TransferMoney\TransferMoney;
use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
use App\Http\Models\MoneyTransfer\SetupMaster\DeliveryMethod;
use App\Http\Models\MoneyTransfer\Currency\Currency;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
use App\Helpers\common;
use Auth;

class TransferMoneyController extends Controller
{
    public function index()
    {
        $TransferMoneyArr = $this->getData($account_id='');
        return response()->json(['success'=>true,'data'=>$TransferMoneyArr,'total'=>count($TransferMoneyArr)]);
    }

    public function getData($account_id=''){
        if($account_id=='')
            $TransferMoney=TransferMoney::OrderBy('account_transfer_id','desc')->get();
        else
            $TransferMoney=TransferMoney::OrderBy('account_transfer_id','desc')->where('account_id',$account_id)->first();

        $TransferMoneyArr = array();
        $i = 1;
        foreach ($TransferMoney as $tm) {
            $TransferMoneyArr[] = array(
                'id' => $i,
                'transfer_no' => $tm->transfer_no,
                'transfer_from_account_no' => Account::fetchAccount($tm->transfer_from_account_no,"account_no"),
                'transfer_to_account_no' => Account::fetchAccount($tm->transfer_to_account_no,"account_no"),
                'delivery_method' => $tm->delivery_method,
                'transfer_from_currency' => $tm->TransferFromCurrency->title,
                'transfer_to_currency' => $tm->TransferToCurrency->title,
                'transfer_amount' => $tm->transfer_amount,
                'receiving_amount' => $tm->receiving_amount,
                'status' => $tm->status
            );
            $i++;
        }

        return $TransferMoneyArr;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $Account = Account::Where('account_id',$input['transfer_from_account_no'])->first();
        
        $checkAccount = Account::checkAccount($Account->account_no,$input['transfer_to_account_no']);
        if($checkAccount){
            if(common::calculateSaveAccount($Account->account_no,floatval($input['transfer_amount']))){
                $input['transfer_no']=config_transfer_prefix.rand(1000, 9999);
                $input['transfer_fee'] = common::getAmountConvertCurrency($input['transfer_fee'],Account::getCurrencyByAccountId(Account::fetchAccount($input['transfer_from_account_no'],'account_id'),'currency_id'),config_currency);
                $input['transfer_date'] = date('Y-m-d H:i:s');
                $input['created_by']=Auth::user()->id;
                $input['receiving_amount'] = $input['transfer_amount'];
                $input['transfer_from_account_no'] = $Account->account_no;
                $input['transfer_from_currency_id'] = $Account->currency_id;
                $input['transfer_to_currency_id'] = Account::getCurrencyByAccountNo($input['transfer_to_account_no'],'currency_id');
                $data['success'] = true;
                $data['message'] = "Transfer Successfully.";
                $data['transfer_info'] = $input;
                $message = "Transfer Money.";
                $transaction_type = 'Transfer Money';
                $DoTransactionByAccount = common::DoTransactionByAccount(0,$input['transfer_from_account_no'],$input['transfer_amount'],$message,$transaction_type);
                common::DoUpdateAccountTransferTo($input['transfer_to_account_no'],$input['transfer_amount']);
                $TransferMoney = TransferMoney::create($input);
                $data['data'] = $TransferMoney;
                // $AccountMobileLog = common::AccountMobileLog($account_master->merchant_id,$token);
                return response()->json($data, 200);
            }else{
                $data['success'] = false;
                $data['message'] = "Transfer account fail, Insuficient balance!";
                return response()->json($data, 200);
            }
        }else{
            $data['success'] = false;
            $data['message'] = "Transfer account fail, no such account!";
            $data['transfer_info'] = $input;
            return response()->json($data, 200);
        }
        
    }
    public function show($id)
    {
        $TransferMoneyArr = $this->getData($id);
        return response()->json($TransferMoneyArr);
    }
    public function edit($id)
    {
        return (new DataAction)->EditData(TransferMoney::class,$id);
    }
    public function update(Request $request,$id)
    {
        $input = $request->all();
        $data=(new TransferMoney)->getFillable();
        $data=$request->only($data);
        $input['transfer_fee'] = common::getAmountConvertCurrency($input['transfer_fee'],Account::getCurrencyByAccountId(Account::fetchAccount($input['transfer_from_account_no'],'account_id'),'currency_id'),config_currency);
        return (new DataAction)->UpdateData(TransferMoney::class,$data,'account_credit_id',$id);
    } 
    public function destroy($id)
    {
        
    }
}
