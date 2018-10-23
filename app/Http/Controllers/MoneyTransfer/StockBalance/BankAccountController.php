<?php

namespace App\Http\Controllers\MoneyTransfer\StockBalance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\StockBalance\BankAccount;
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
class BankAccountController extends Controller
{
    public function index()
    {
        $BankAccount = BankAccount::OrderBy('bank_account_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$BankAccount,'total'=>count($BankAccount)]);
    }
    public function store(Request $request)
    {
        $data=(new BankAccount)->getFillable();
        $data=$request->only($data);
        $condition=[

        ];

        return (new DataAction)->StoreData(BankAccount::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(BankAccount::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new BankAccount)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(BankAccount::class,$data,'bank_account_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(BankAccount::class,'bank_account_id',$id);
    }
}
