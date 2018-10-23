<?php

namespace App\Http\Controllers\MoneyTransfer\Catalog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Catalog\Category;
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
class CategoryController extends Controller
{
    public function index()
    {
        $Categories = Category::OrderBy('category_id','desc')->get();        
        $data = array();
        foreach($Categories as $Category){
            $category_name = '';
            if($Category->parent_id>0){
                $parent_category = Category::where('category_id',$Category->parent_id)->first();
                $category_name = $parent_category->name.' -> '.$Category->name;
            }else{
                $category_name = $Category->name;
            }
            $data[] = array(
                'category_id'=>$Category->category_id,
                'parent_id'=>$Category->parent_id,
                'order_level'=>$Category->order_level,
                'name'=>$category_name,
                'status'=>$Category->status
            );
        }  
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }
    public function store(Request $request)
    {
        $data=(new Category)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(Category::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Category::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new Category)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(Category::class,$data,'category_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(Category::class,'category_id',$id);
    }
}
