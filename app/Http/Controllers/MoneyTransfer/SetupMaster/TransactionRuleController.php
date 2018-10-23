<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\TransactionRule;
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
class TransactionRuleController extends Controller
{
    public function index()
    {
        $TransactionRules = TransactionRule::OrderBy('transaction_rule_id','desc')->get();
        $data = array();
        foreach($TransactionRules as $TransactionRule){
            $data[] = array(
                'transaction_rule_id'=>$TransactionRule->transaction_rule_id,
                'transaction_rule_name'=>$TransactionRule->transaction_rule_name,
                'currency'=>$TransactionRule->Currency->code,
                'country'=>$TransactionRule->Country->name,
                'rule_type'=>$TransactionRule->RuleType->name,
                'restrict_type'=>$TransactionRule->RestrictType->name,
                'delivery_method'=>$TransactionRule->DeliveryMethod->name,
                'amount_limit'=>$TransactionRule->amount_limit,
                'remark'=>$TransactionRule->remark
            );
        }    
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }
    public function store(Request $request)
    {
        // $input = $request->all();
        $data=(new TransactionRule)->getFillable();
        $data=$request->only($data);
        // $data=$request['data'];
        // $data["name"]=$input['name'];
        // $data["iso_code_2"]=$input['iso_code_2'];
        // $data["iso_code_3"]=$input['iso_code_3'];
        // $data["address_format"]=$input['address_format'];
        // $data["postcode_required"]=$input['postcode_required'];
        // $data["status"]=$input['status'];
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(TransactionRule::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(TransactionRule::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new TransactionRule)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(TransactionRule::class,$data,'transaction_rule_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(TransactionRule::class,'transaction_rule_id',$id);
    }
}
