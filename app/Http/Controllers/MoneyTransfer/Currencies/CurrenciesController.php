<?php

namespace App\Http\Controllers\MoneyTransfer\Currencies;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Currency\Currency;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class CurrenciesController extends Controller
{
    public function index()
    {
        $Currencies=Currency::all();
        $data = array();
        // dd($GetExchangeRate);
        foreach($Currencies as $currency){
            $GetExchangeRate = '';
            if(Currency::GetExchangeRate($currency->currency_id)){
                $GetExchangeRate = Currency::GetExchangeRate($currency->currency_id);
                $buy_in = $GetExchangeRate->buy_in;
                $sell_out = $GetExchangeRate->sell_out;
            }else{
                $GetExchangeRate = 0;
                $buy_in = 0;
                $sell_out = 0;
            }

            $data[] = array(
                "currency_id" => $currency->currency_id,
                "title" => $currency->title,
                "code" => $currency->code,
                "buy_in" => $buy_in,
                "sell_out" => $sell_out,
                "symbol_left" => $currency->symbol_left,
                "symbol_right" => $currency->symbol_right,
                "decimal_place" => $currency->decimal_place,
                "value" => $currency->value,
                "is_default" => $currency->is_default,
                "status" => $currency->status,
                "date_modified" => $currency->date_modified
            );
        }
        return response()->json(['success'=>true,'message'=>'Success','data'=>$data]);
    }

    public function getCurrency(){
        return Currency::select('currency_id as value','code as text')->get();
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
