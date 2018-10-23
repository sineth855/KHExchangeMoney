<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\AmountTopUp;
use App\Http\Models\MoneyTransfer\SetupMaster\Country;
use App\Http\Models\MoneyTransfer\SetupMaster\Currency;
use App\Http\Models\MoneyTransfer\SetupMaster\Voucher;
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
class AmountTopUpController extends Controller
{
    public function index()
    {
        $AmountTopUps = AmountTopUp::OrderBy('order_level')->get();
        $data = array();
        foreach($AmountTopUps as $AmountTopUp){
            $data[] = array(
                'amount_top_up_id'=>$AmountTopUp->amount_top_up_id,
                'country'=>$AmountTopUp->Country->name,
                'currency'=>$AmountTopUp->Currency->title,
                'voucher'=>$AmountTopUp->Voucher->name,
                'name'=>$AmountTopUp->name,
                'order_level'=>$AmountTopUp->order_level
            );
        } 
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($AmountTopUp)]);
    }
    public function store(Request $request)
    {
        $data=(new AmountTopUp)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(AmountTopUp::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(AmountTopUp::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new AmountTopUp)->getFillable();
        $data=$request->only($data);
        if(@$data['image']){
            $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        }
        return (new DataAction)->UpdateData(AmountTopUp::class,$data,'amount_top_up_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(AmountTopUp::class,'amount_top_up_id',$id);
    }
}
