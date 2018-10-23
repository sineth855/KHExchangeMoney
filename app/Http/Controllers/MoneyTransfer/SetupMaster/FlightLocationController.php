<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\FlightLocation;
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
class FlightLocationController extends Controller
{
    public function index()
    {
        $FlightLocations = FlightLocation::OrderBy('id','desc')->get();      
        $data = array();
        foreach($FlightLocations as $row){
            $data[]  = array(
                "id" => $row->id,
                "country" => $row->Country->name,
                "name" => $row->name,
                "is_active" => $row->is_active
            );
        }
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }
    function getFlyLocationBaseCountry(Request $request){
        $country_id = $request['country_id'];
        $FlightLocation = FlightLocation::Where('country_id',$country_id)->OrderBy('id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$FlightLocation,'total'=>count($FlightLocation)]);
    }
    public function store(Request $request)
    {
        $data=(new FlightLocation)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(FlightLocation::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(FlightLocation::class,$id);
    }
    public function update(Request $request,$id)
    {
        
        $data=(new FlightLocation)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(FlightLocation::class,$data,'id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(FlightLocation::class,'id',$id);
    }
}
