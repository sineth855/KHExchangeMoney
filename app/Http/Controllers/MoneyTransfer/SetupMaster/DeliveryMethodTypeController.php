<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\DeliveryMethodType;
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
class DeliveryMethodTypeController extends Controller
{
    public function index()
    {
        $DeliveryMethodTypes = DeliveryMethodType::OrderBy('delivery_method_type_id','desc')->get();        
        $data_arr = array();
        foreach($DeliveryMethodTypes as $DeliveryMethodType){
            $data_arr[]  = array(
                "delivery_method_type_id" => $DeliveryMethodType->delivery_method_type_id,
                "name" => $DeliveryMethodType->name
            );
        }
        return response()->json(['success'=>true,'message'=>'Success','data'=>$data_arr,'total'=>count($data_arr)]);
    }
    public function store(Request $request)
    {
        $data=(new DeliveryMethodType)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(DeliveryMethodType::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(DeliveryMethodType::class,$id);
    }
    public function update(Request $request,$id)
    {
        
        $data=(new DeliveryMethodType)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(DeliveryMethodType::class,$data,'delivery_method_type_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(DeliveryMethodType::class,'delivery_method_type_id',$id);
    }
}
