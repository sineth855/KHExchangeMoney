<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\Country;
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
class CountryController extends Controller
{
    public function index()
    {
        $Countries = Country::OrderBy('name','asc')->get();
        $data = array();
        foreach($Countries as $Country){
            $data[] = array(
                "country_id"=> $Country->country_id,
                "name"=> $Country->name,
                "iso_code_2"=> $Country->iso_code_2,
                "iso_code_3"=> $Country->iso_code_3,
                "country_code"=> $Country->country_code,
                "image"=> config_url.'/'.$Country->image,
                "address_format"=> $Country->address_format,
                "postcode_required"=> $Country->postcode_required,
                "status"=> $Country->status
            );
        }    
        return response()->json(['success'=>true,'data'=>$data,'message'=>'success','total'=>count($data)]);
    }
    public function store(Request $request)
    {
        // $input = $request->all();
        $data=(new Country)->getFillable();
        $data=$request->only($data);
        // $data=$request['data'];
        // $data["name"]=$input['name'];
        // $data["iso_code_2"]=$input['iso_code_2'];
        // $data["iso_code_3"]=$input['iso_code_3'];
        // $data["address_format"]=$input['address_format'];
        // $data["postcode_required"]=$input['postcode_required'];
        // $data["status"]=$input['status'];
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(Country::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Country::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new Country)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(Country::class,$data,'country_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(Country::class,'country_id',$id);
    }
}
