<?php

namespace App\Http\Controllers\MoneyTransfer\Currencies;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Currency\ExchangeRateHistory;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class ExchangeRateHistoryController extends Controller
{
    public function index()
    {
        $ExchangeRateHistorys=ExchangeRateHistory::OrderBy('date_added','desc')->get();
        return response()->json(['success'=>true,'message'=>'Success','data'=>$ExchangeRateHistorys,'total'=>count($ExchangeRateHistorys)]);
    }

    public function getCurrency(){
        return ExchangeRateHistory::select('currency_id as value','code as text')->get();
    }

    public function store(Request $request)
    {
        $data=(new Currency)->getFillable();
        $data=$request->only($data);
        
        $condition=[
            'title'=>$data['title']
        ];

        return (new DataAction)->StoreData(ExchangeRateHistory::class,$condition,"",$data);
        //return response()->json($data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(ExchangeRateHistory::class,$id);
    }
    public function update(Request $request,$id)
    {
         $data=(new Currency)->getFillable();
        $data=$request->only($data);
         return (new DataAction)->UpdateData(ExchangeRateHistory::class,$data,'currency_id',$id);
    }
    public function destroy($id)
    {
        return (new DataAction)->DeleteData(ExchangeRateHistory::class,'currency_id',$id);
    }
}
