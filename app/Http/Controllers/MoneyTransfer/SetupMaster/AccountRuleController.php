<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\AccountRule;
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
class AccountRuleController extends Controller
{
    public function index()
    {
        $AccountRules = AccountRule::OrderBy('account_rule_id','desc')->get();
        $data = array();
        foreach($AccountRules as $row){
            $data[] = array(
                "account_rule_id"=>$row->account_rule_id,
                "name"=>$row->name,
                "currency"=>$row->Currency->title,
                "min_amount_allowed"=>$row->min_amount_allowed,
                "max_amount_allowed"=>$row->max_amount_allowed,
                "remark"=>$row->remark
            );
        }   
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }
    public function store(Request $request)
    {
        $data=(new AccountRule)->getFillable();
        $data=$request->only($data);

        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(AccountRule::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(AccountRule::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new AccountRule)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(AccountRule::class,$data,'account_rule_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(AccountRule::class,'account_rule_id',$id);
    }
}
