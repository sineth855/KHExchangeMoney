<?php

namespace App\Http\Controllers\MoneyTransfer\StockBalance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\StockBalance\AccountOwnerTransfer;
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
class AccountOwnerTransferController extends Controller
{
    public function index()
    {
        $AccountOwnerTransfer = AccountOwnerTransfer::OrderBy('account_owner_transfer_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$AccountOwnerTransfer,'total'=>count($AccountOwnerTransfer)]);
    }
    public function store(Request $request)
    {
        $data=(new AccountOwnerTransfer)->getFillable();
        $data=$request->only($data);
        $condition=[

        ];

        return (new DataAction)->StoreData(AccountOwnerTransfer::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(AccountOwnerTransfer::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new AccountOwnerTransfer)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(AccountOwnerTransfer::class,$data,'account_owner_transfer_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(AccountOwnerTransfer::class,'account_owner_transfer_id',$id);
    }
}
