<?php

namespace App\Http\Controllers\MoneyTransfer\MerchantAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\MerchantAccount\AccountNotification;
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
class AccountNotificationController extends Controller
{
    public function index()
    {
        $AccountNotifications = AccountNotification::where('is_read','0')->orderBy('transaction_date','desc')->get();
        $data = array();
        foreach($AccountNotifications as $row){
            $data[] = array(
                "account_notification_id"=>$row->account_notification_id,
                "notification_type"=>$row->notification_type,
                "account_no"=>$row->account_no,
                "notification_title"=>$row->notification_title,
                "transaction_amount"=>$row->transaction_amount,
                "currency"=>$row->currency,
                "transaction_detail"=>$row->transaction_detail,
                "is_read"=>$row->is_read,
                "transaction_date"=>$row->transaction_date
            );
        }   
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }
    public function store(Request $request)
    {
        $data=(new AccountNotification)->getFillable();
        $data=$request->only($data);

        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(AccountNotification::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(AccountNotification::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new AccountNotification)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(AccountNotification::class,$data,'account_notification_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(AccountNotification::class,'account_notification_id',$id);
    }
}
