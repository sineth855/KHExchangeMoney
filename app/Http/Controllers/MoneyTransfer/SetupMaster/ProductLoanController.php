<?php

namespace App\Http\Controllers\MoneyTransfer\SetupMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\SetupMaster\ProductLoan;
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
class ProductLoanController extends Controller
{
    public function index()
    {
        $ProductLoan = ProductLoan::OrderBy('loan_product_id','desc')->get();        
        return response()->json(['success'=>true,'data'=>$ProductLoan,'total'=>count($ProductLoan)]);
    }
    public function store(Request $request)
    {
        $data=(new ProductLoan)->getFillable();
        $data=$request->only($data);

        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(ProductLoan::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(ProductLoan::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new ProductLoan)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(ProductLoan::class,$data,'loan_product_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(ProductLoan::class,'loan_product_id',$id);
    }
}
