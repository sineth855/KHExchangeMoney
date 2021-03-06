<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\PaymentCycle;
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
class PaymentCycleController extends Controller
{
    public function index()
    {
        $PaymentCycle = PaymentCycle::OrderBy('payment_cycle_id','desc')->get();
        return response()->json(['success'=>true,'data'=>$PaymentCycle,'message'=>'success','total'=>count($PaymentCycle)]);
    }
    public function store(Request $request)
    {
        $data=(new PaymentCycle)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(PaymentCycle::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(PaymentCycle::class,$id);
    }
    public function update(Request $request,$id)
    {
        
        $data=(new PaymentCycle)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(PaymentCycle::class,$data,'payment_cycle_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(PaymentCycle::class,'payment_cycle_id',$id);
    }
}
