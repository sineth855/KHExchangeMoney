<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        // $this->middleware('auth:customer');
        $this->middleware('auth:account', ['except' => ['logout']]);
    }
    /**
     * show dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "login";
    }
}