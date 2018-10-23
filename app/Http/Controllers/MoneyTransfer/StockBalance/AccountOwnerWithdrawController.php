<?php

namespace App\Http\Controllers\MoneyTransfer\StockBalance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
class AccountOwnerWithdrawController extends Controller
{
    public function index()
    {
        $AccountOwnerWithdraw = AccountOwnerWithdraw::OrderBy('modified_date','desc')->get();        
        return response()->json(['success'=>true,'data'=>$AccountOwnerWithdraw,'total'=>count($AccountOwnerWithdraw)]);
    }
    public function store(Request $request)
    {
        $data=(new AccountOwnerWithdraw)->getFillable();
        $data=$request->only($data);
        $condition=[

        ];

        return (new DataAction)->StoreData(AccountOwnerWithdraw::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(AccountOwnerWithdraw::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new AccountOwnerWithdraw)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(AccountOwnerWithdraw::class,$data,'modified_date',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(AccountOwnerWithdraw::class,'modified_date',$id);
    }
}
