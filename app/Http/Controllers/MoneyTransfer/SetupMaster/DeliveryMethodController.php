<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\DeliveryMethod;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class DeliveryMethodController extends Controller
{
    public function index()
    {
        $Deliveries=DeliveryMethod::OrderBy('order_level')->get();
        $data_arr = array();
        foreach($Deliveries as $Delivery){
            $data_arr[]  = array(
                "delivery_method_id" => $Delivery->delivery_method_id,
                "delivery_method" => $Delivery->name,
                "delivery_method_type" => $Delivery->DeliveryMethodType->name,
                "delivery_method_type_id" => $Delivery->delivery_method_type_id,
                "name" => $Delivery->name,
                "image" => config_url.'/'.$Delivery->image,
                "order_level" => $Delivery->order_level,
                "created_by" => $Delivery->created_by,
                "modified_by" => $Delivery->modified_by,
                "created_at" => $Delivery->created_at,
                "updated_at" => $Delivery->updated_at
            );
        }
        return response()->json(['success'=>true,'message'=>'Success','data'=>$data_arr,'total'=>count($data_arr)]);
    }
    public function store(Request $request)
    {
        $data=(new DeliveryMethod)->getFillable();
        $data=$request->only($data);
        
        $condition=[
                
        ];

        return (new DataAction)->StoreData(DeliveryMethod::class,$condition,"",$data);
        //return response()->json($data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(DeliveryMethod::class,$id);
    }
    public function update(Request $request,$id)
    {
         $data=(new DeliveryMethod)->getFillable();
        $data=$request->only($data);
         return (new DataAction)->UpdateData(DeliveryMethod::class,$data,'delivery_method_id',$id);
    }
    public function destroy($id)
    {
        return (new DataAction)->DeleteData(DeliveryMethod::class,'delivery_method_id',$id);
    }
}
