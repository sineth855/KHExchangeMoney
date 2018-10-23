<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\InterestMethod;
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
class InterestMethodController extends Controller
{
    public function index()
    {
        $InterestMethods = InterestMethod::OrderBy('interest_method_id','desc')->get();        
        $data = array();
        foreach($InterestMethods as $InterestMethod){
            $data[] = array(
                "interest_method_id"=>$InterestMethod->interest_method_id,
                "name"=>$InterestMethod->name,
                "minimun_cash"=>$InterestMethod->minimun_cash,
                "maxinum_cash"=>$InterestMethod->maxinum_cash,
                "fix_charge"=>$InterestMethod->fix_charge,
                "currency_id"=>$InterestMethod->currency_id,
                "currency"=>$InterestMethod->Currency->title,
                "percentage"=>$InterestMethod->percentage,
                "processing_period"=>$InterestMethod->processing_period,
                "term_day_id"=>$InterestMethod->term_day_id,
                "term_day"=>$InterestMethod->TermDay->name
            );
        }
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($InterestMethods)]);
    }
    public function store(Request $request)
    {
        $data=(new InterestMethod)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(InterestMethod::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(InterestMethod::class,$id);
    }
    public function update(Request $request,$id)
    {
        
        $data=(new InterestMethod)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(InterestMethod::class,$data,'interest_method_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(InterestMethod::class,'interest_method_id',$id);
    }
}
