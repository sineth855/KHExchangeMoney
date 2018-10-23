<?php

namespace App\Http\Controllers\MoneyTransfer\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MoneyTransfer\Location\Location;

class LocationController extends Controller
{
    public function index()
    {
        $Location = Location::all();
        return response()->json(['success'=>true,'message'=>'Success.','map'=>$Location],200);
    }
}
