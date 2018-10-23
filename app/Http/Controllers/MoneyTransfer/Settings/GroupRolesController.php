<?php

namespace App\Http\Controllers\MoneyTransfer\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\FrontEnd\SessionModel;
use App\Helpers\common;
// use Helpers;

class GroupRolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $getMenus = common::getMenus(1,1);
        $data = array('data'=>$getMenus);
        return response()->json($data);
        // return $getMenus
    }

    
}
