<?php

namespace App\Http\Controllers\MoneyTransfer\Taxs\TaxRate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\BackEnd\Tax\TaxRate\TaxRate;
/*
    DataAction class use for any action the data from any table
    For more detail i have comment in DataAction class in commons folder
*/
use App\Http\Controllers\Backend\commons\DataAction;
class TaxRateController extends Controller
{
    
    public function index()
    {

        $TaxRate=TaxRate::AllTaxRate();

        return response()->json($TaxRate);
    }

    public function show($id){
        return response()->json([]);
    }

    public function store(Request $request)
    {

        $data=(new TaxRate)->getFillable();
        $data=$request->only($data);

        $condition=[
            'name'=>$data['name']
        ];

        return (new DataAction)->StoreData(TaxRate::class,$condition,'',$data);

    }

    public function edit($id)
    {
        return (new DataAction)->EditData(TaxRate::class,$id);
        
    }

    public function update(Request $request,$id)
    {
        
        $data=(new TaxRate)->getFillable();
        $data=$request->only($data);

        return (new DataAction)->UpdateData(TaxRate::class,$data,'tax_rate_id',$id);

    }

    public function destroy($id)
    {

        return (new DataAction)->DeleteData(TaxRate::class,'tax_rate_id',$id);
        
    }
}
