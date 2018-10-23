<?php

namespace App\Http\Controllers\MoneyTransfer\UserGroups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Models\MoneyTransfer\User\User;
use App\Http\Models\MoneyTransfer\UserGroup\UserGroup;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\MoneyTransfer\commons\ImageMaker;
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

class UserGroupsController extends Controller
{
    public function index()
    {
        $UserGroups = UserGroup::all();
        return response()->json(['success'=>true,'data'=>$UserGroups,'total'=>count($UserGroups)]);
    }

    public function store(Request $request)
    {

      //$ImageMaker=new ImageMaker;
      //$ImageMaker->base64ToImage("imagesss",$request->userImage);
      //     if( preg_match('/data:image/', $request->userImage) ){                
      //     preg_match('/data:image\/(?<mime>.*?)\;/', $request->userImage , $groups);
      //     $mimetype = $groups['mime'];
                       
      //     $image='images/TestImage.'.$mimetype;
      //     $image = Image::make($request->userImage)
      //       ->fit(400, 500) 
      //       ->encode($mimetype, 100) 
      //       ->save(public_path($image));                
      // }
        
        // $data=array();
        // $condition=array();
       
        $data=(new UserGroup)->getFillable();
        $data=$request->only($data);
        $condition=[
            //'key'=>$data['key']
        ];

        return (new DataAction)->StoreData(UserGroup::class,$condition,'',$data);
        //return response()->json($data);

    }
    public function edit($id)
    {

        return (new DataAction)->EditData(UserGroup::class,$id);

    }
    
    public function update(Request $request,$id)
    {
        // if( preg_match('/data:image/', $request->userImage) ){             
     //      preg_match('/data:image\/(?<mime>.*?)\;/', $request->userImage , $groups);
     //      $mimetype = $groups['mime'];
                       
     //      $firstname='images/testpost.'.$mimetype;
     //      $image = Image::make($request->userImage)
     //        // ->fit(400, 500) 
     //        ->encode($mimetype, 100) 
     //        ->save(public_path($firstname));                
     //    } 
        $data=(new UserGroup)->getFillable();
        $data=$request->only($data);
        return (new DataAction)->UpdateData(UserGroup::class,$data,'user_group_id',$id);
        // return response()->json($data);
    }
    public function destroy($id)
    {
        return (new DataAction)->DeleteData(UserGroup::class,'user_group_id',$id);
    }
    public function UserGroup()
    {
        return response()->json(UserGroup::Groups());
    }
    public function ValidateData($field,$value){
        
        $existed=false;

        //instant the object
        $validate=new ValidateDataController;
        if($field=="username"){
            //return data json to vuejs when axios request
            return $validate->CheckDataExist('user','username',$value);    
        }elseif($field=="email"){
            //return data json to vuejs when axios request
            return $validate->CheckDataExist('user','email',$value);
        }
        
    }
}
