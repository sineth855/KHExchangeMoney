<?php

namespace App\Http\Controllers\MoneyTransfer\Catalog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Catalog\Product;
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
class ProductController extends Controller
{
    public function index()
    {
        $Products = Product::OrderBy('product_id','desc')->get();
        $data = array();
        foreach($Products as $product){
            $category_name = '';
            $category = Category::where('category_id',$product->category_id)->first();
            if($category->parent_id!=0){
                $parent_category = Category::where('category_id',$category->parent_id)->first();
                $category_name = $parent_category->name.'->'.$category->name;
            }else{
                $category_name = $category->name;
            }
            $data[] = array(
                'product_id'=>$product->product_id,
                'name'=>$product->name,
                'category'=>$category_name,
                'model'=>$product->name,
                'cost'=>$product->cost,
                'price'=>$product->price,
                'currency'=>config_currency_code,
                'manufacturer'=>$product->manufacturer,
                'quantity'=>$product->quantity,
                'stock_status'=>$product->StockStatus->name
            );
        }   
        return response()->json(['success'=>true,'data'=>$data,'total'=>count($data)]);
    }

    public function getProductBaseCategory($category_id){
        $Products = Product::Where('category_id',$category_id)->OrderBy('name')->get();
        return response()->json(['success'=>true,'data'=>$Products,'total'=>count($Products)]);
    }
    public function store(Request $request)
    {
        $data=(new Product)->getFillable();
        $data['date_modified'] = date("Y-m-d");
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(Product::class,$condition,'',$data);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        return (new DataAction)->EditData(Product::class,$id);
    }
    public function update(Request $request,$id)
    {
        $data=(new Product)->getFillable();
        $data=$request->only($data);
        // if(@$data['image']){
        //     $data['image']=(new ImageMaker)->base64ToImage('images\\icon',$data['image']);    
        // }
        return (new DataAction)->UpdateData(Product::class,$data,'product_id',$id);
    }
    public function destroy($id)
    {
    	return (new DataAction)->DeleteData(Product::class,'product_id',$id);
    }
}
