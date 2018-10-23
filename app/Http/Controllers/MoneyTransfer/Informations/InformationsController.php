<?php

namespace App\Http\Controllers\MoneyTransfer\Informations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Backend\Information\Information;
use App\Http\Models\Backend\Information\InformationDescription;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\Backend\commons\DataAction;
class InformationsController extends Controller
{
    public function index()
    {
        $Information=Information::AllInformations();
        return response()->json($Information);
    }
    public function store(Request $request)
    {
        //data for Information value
        $Information=(new Information)->getFillable();
        $Information=$request->only($Information);

        //Data for Information description
        $FilterDesc=(new InformationDescription)->getFillable();
        $FilterDesc=$request->only($FilterDesc);
        
        //condition to check if Information value is already existed
        $filterGroup=[
            'name'=>$request->name
        ];
        
        //save Information value and return filter_id to insert to Information description
        $saveFilter = (new DataAction)->StoreData(Information::class,[],"",$Information,"filter_id");

        //if Information value is insert successfull
        if($saveFilter['success']){
            
            //get id from child array(data)
            $FilterDesc['filter_id'] = $saveFilter['filter_id'];

            //return success message if data have been successfully save to database
            return (new DataAction)->StoreData(InformationDescription::class,[],"",$FilterDesc); 

        }else{

            //if data doesn't saved to database this will return success false and message why data not save
            return $saveFilter;

        }
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $Information=Information::InformationEdit($id);
        foreach($Information as $Information){
        	$Information=$Information;
        }
        return response()->json($Information);
    }
    public function update(Request $request,$id)
    {
        //data for Information value
        $Information=(new Information)->getFillable();
        $Information=$request->only($Information);

        //Data for Information description
        $attributeDesc=(new InformationDescription)->getFillable();
        $attributeDesc=$request->only($attributeDesc);

        $saveFilter = (new DataAction)->UpdateData(Information::class,$Information,'filter_id',$id);
    	return (new DataAction)->UpdateData(InformationDescription::class,$attributeDesc,'filter_id',$id);
    } 
    public function destroy($id)
    {
        (new DataAction)->DeleteData(Information::class,'filter_id',$id);
        return (new DataAction)->DeleteData(InformationDescription::class,'filter_id',$id);
    }
}
