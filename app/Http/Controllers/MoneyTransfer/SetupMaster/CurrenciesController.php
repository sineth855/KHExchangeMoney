<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\Currency;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class CurrenciesController extends Controller
{
    public function index()
    {
        $Currencies=Currency::OrderBy('currency_id','desc')->get();
        return response()->json(['success'=>true,'data'=>$Currencies,'total'=>count($Currencies)]);
    }
    public function store(Request $request)
    {
        $data=(new Currency)->getFillable();
        $data=$request->only($data);
        $data['date_modified'] = date("Y-m-d H:i:s");
        $condition=[
            'title'=>$data['title']
        ];

        return (new DataAction)->StoreData(Currency::class,$condition,"",$data);
        //return response()->json($data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Currency::class,$id);
    }
    public function update(Request $request,$id)
    {
         $data=(new Currency)->getFillable();
         $data=$request->only($data);
         $data['date_modified'] = date("Y-m-d H:i:s");
         return (new DataAction)->UpdateData(Currency::class,$data,'currency_id',$id);
    }
    public function destroy($id)
    {
        return (new DataAction)->DeleteData(Currency::class,'currency_id',$id);
    }
}
