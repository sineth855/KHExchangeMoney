<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\InterestRatePeriod;
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
class InterestRatePeriodController extends Controller
{
    public function index()
    {
        $InterestRatePeriod = InterestRatePeriod::OrderBy('interest_rate_period_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$InterestRatePeriod,'total'=>count($InterestRatePeriod)]);
    }
    public function store(Request $request)
    {
        $data=(new InterestRatePeriod)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(InterestRatePeriod::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(InterestRatePeriod::class,$id);
    }
    public function update(Request $request,$id)
    {
        
        $data=(new InterestRatePeriod)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(InterestRatePeriod::class,$data,'interest_rate_period_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(InterestRatePeriod::class,'interest_rate_period_id',$id);
    }
}
