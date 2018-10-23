<?php

namespace App\Http\Controllers\MoneyTransfer\Currencies;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Currency\ExchangeRate;
use App\Http\Models\MoneyTransfer\Currency\ExchangeRateHistory;
use App\Http\Models\MoneyTransfer\Currency\Currency;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\MoneyTransfer\commons\DataAction;
class ExchangeRateController extends Controller
{
    public function index()
    {
        $ExchangeRates=ExchangeRate::all();
        $data = array();
        // dd($GetExchangeRate);
        foreach($ExchangeRates as $ExchangeRate){
            $data[] = array(
                "exchange_rate_id"=>$ExchangeRate->exchange_rate_id,
                "from_currency_id"=>$ExchangeRate->from_currency_id,
                "from_currency"=>$ExchangeRate->ExchangeFromCurrency->title,
                "to_currency_id"=>$ExchangeRate->to_currency_id,
                "to_currency"=>$ExchangeRate->ExchangeToCurrency->title,
                "buy_in"=>$ExchangeRate->buy_in,
                "sell_out"=>$ExchangeRate->sell_out,
                "rate"=>$ExchangeRate->rate,
            );
        }
        return response()->json(['success'=>true,'message'=>'Success','data'=>$data]);
    }

    public function getCurrency(){
        return ExchangeRate::select('currency_id as value','code as text')->get();
    }

    public function store(Request $request)
    {
        $data=(new Currency)->getFillable();
        $data=$request->only($data);
        
        $condition=[
            'title'=>$data['title']
        ];

        ExchangeRateHistory::create([
            "from_currency" => Currency::GetCurrencyBaseId($request->from_currency_id,'title'),
            "to_currency" => Currency::GetCurrencyBaseId($request->to_currency_id,'title'),
            "buy_in" => $request->buy_in,
            "sell_out" => $request->sell_out,
            "rate" => $request->rate,
            "date_added"=>Date('Y-m-d')
        ]);

        return (new DataAction)->StoreData(ExchangeRate::class,$condition,"",$data);
        //return response()->json($data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(ExchangeRate::class,$id);
    }
    public function update(Request $request,$id)
    {
         $data=(new ExchangeRate)->getFillable();
         $data=$request->only($data);
         ExchangeRateHistory::create([
            "from_currency" => Currency::GetCurrencyBaseId($request->from_currency_id,'title'),
            "to_currency" => Currency::GetCurrencyBaseId($request->to_currency_id,'title'),
            "buy_in" => $request->buy_in,
            "sell_out" => $request->sell_out,
            "rate" => $request->rate,
            "date_added"=>Date('Y-m-d')
         ]);
         return (new DataAction)->UpdateData(ExchangeRate::class,$data,'exchange_rate_id',$id);
    }
    public function destroy($id)
    {
        return (new DataAction)->DeleteData(ExchangeRate::class,'exchange_rate_id',$id);
    }
}
