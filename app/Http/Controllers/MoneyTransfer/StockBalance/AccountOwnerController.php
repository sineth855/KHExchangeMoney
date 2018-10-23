<?php

namespace App\Http\Controllers\MoneyTransfer\StockBalance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\StockBalance\AccountOwner;
use App\Http\Models\MoneyTransfer\StockBalance\AccountOwnerCredit;
use App\Http\Models\MoneyTransfer\StockBalance\AccountOwnerHistory;
use App\Http\Models\MoneyTransfer\StockBalance\AccountOwnerTransfer;
use App\Http\Models\MoneyTransfer\StockBalance\AccountOwnerWithdraw;
/*
    this controller use for create any validation function
    currently it have one function to validate data if exist or not yet exist
    then return the json to pass to axios.get() in veujs
    this function there are 3 parameter(tablename,fieldname,value)
        - tablename: table that we want to check
        - fieldname: field of that table we want to filter
        - value: value of field we want to check
*/
use App\Http\Controllers\MoneyTransfer\commons\ValidateDataController;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class AccountOwnerController extends Controller
{
    public function index()
    {
        $AccountOwners = AccountOwner::OrderBy('account_owner_id','desc')->get(); 
        $data = array();       
        foreach($AccountOwners as $AccountOwner){
            $data[] = array(
                "account_owner_id"=>$AccountOwner->account_owner_id,
                "bank_account_id"=>$AccountOwner->bank_account_id,
                "bank_name"=>$AccountOwner->BankAccount->bank_name,
                "bank_account_name"=>$AccountOwner->BankAccount->account_name,
                "name"=>$AccountOwner->name,
                "available_credit"=>$AccountOwner->available_credit,
                "currency"=>$AccountOwner->Currency->title,
                "currency_id"=>$AccountOwner->currency_id,
                "remark"=>$AccountOwner->remark,
                "stock_status_id"=>$AccountOwner->stock_status_id,
                "stock_status"=>$AccountOwner->StockStatus->name,
                "is_active"=>$AccountOwner->is_active
            );
        }
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($AccountOwners)]);
    }

    public function getAccountDetail($id){
        $AccountOwnerCredit=[];
        $AccountOwnerTransfer=[];
        $AccountOwnerWithdraw=[];
        $AccountOwnerHistory=[];

        if(AccountOwnerCredit::where('account_owner_id',$id)->get()){
            $AccountOwnerCredit = AccountOwnerCredit::where('account_owner_id',$id)->get();   
        }
        if(AccountOwnerTransfer::where('transfer_from_account_owner_id',$id)->get()){
            $AccountOwnerTransfer = AccountOwnerTransfer::where('transfer_from_account_owner_id',$id)->get();   
        }
        if(AccountOwnerWithdraw::where('account_owner_id',$id)->get()){
            $AccountOwnerWithdraw = AccountOwnerWithdraw::where('account_owner_id',$id)->get();   
        }
        if(AccountOwnerHistory::where('account_owner_id',$id)->get()){
            $AccountOwnerHistory = AccountOwnerHistory::where('account_owner_id',$id)->get();
        }

        $data = array(
            'account_credit'=>$AccountOwnerCredit,
            'account_transfer'=>$AccountOwnerTransfer,
            'account_withdraw'=>$AccountOwnerWithdraw,
            'account_history'=>$AccountOwnerHistory
        );
        return response()->json(['success'=>true,'data'=>$data,'message'=>'Success.']);
    }
    
    public function store(Request $request)
    {
        $data=(new AccountOwner)->getFillable();
        $data=$request->only($data);
        $condition=[

        ];
        return (new DataAction)->StoreData(AccountOwner::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(AccountOwner::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new AccountOwner)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(AccountOwner::class,$data,'account_owner_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(AccountOwner::class,'account_owner_id',$id);
    }
}
