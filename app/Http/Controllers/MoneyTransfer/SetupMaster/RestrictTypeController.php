<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\RestrictType;
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
class RestrictTypeController extends Controller
{
    public function index()
    {
        $RestrictType = RestrictType::OrderBy('restrict_type_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$RestrictType,'total'=>count($RestrictType)]);
    }
    public function store(Request $request)
    {
        $data=(new RestrictType)->getFillable();
        $data=$request->only($data);

        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(RestrictType::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(RestrictType::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new RestrictType)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(RestrictType::class,$data,'restrict_type_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(RestrictType::class,'restrict_type_id',$id);
    }
}
