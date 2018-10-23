<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Account;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;

    class AccountController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function getAccount(){
            $success['user'] = Auth::user();
            return response()->json(['success'=>$success], 200);
        }
    }