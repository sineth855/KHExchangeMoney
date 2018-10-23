<?php

namespace App\Http\Controllers\MoneyTransfer\Voip;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Voip\Voip;

class VoipController extends Controller
{
    public function index()
    {
        $Voips = Voip::all();
        $data = array();
        $i=1;
        foreach($Voips as $Voip){
            $data[] = array(
                'id' => $i,
                'buy_voip_id' => $Voip->buy_voip_id,
                'account_no' => $Voip->account_no,
                'currency' => $Voip->Currency->code,
                'amount' => $Voip->amount,
                'status' => $Voip->status,
                'date_added' => $Voip->date_added
            );
            $i++;
        }
        return response()->json($data);
    }
    public function store(Request $request)
    {
        $data=(new Voip)->getFillable();
        $data=$request->only($data);

        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(Voip::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Voip::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new Voip)->getFillable();
        $data=$request->only($data);
        if(@$data['image']){
            $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        }
        return (new DataAction)->UpdateData(Voip::class,$data,'account_rule_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(Voip::class,'account_rule_id',$id);
    }
}

