<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Restponse;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')
            ->only('logout');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|between6,25|confirmed'
        ]);

        $user = new User($request->all());

        $user->password = bcrypt($request->password);
        $user->save();

        return response()

            ->json([
                'registered' => true,
            ]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|between6,25'
        ]);

        $user = User::where('email', $request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $user->api_token = str_random(60);
            $user->save();

            return response()

                ->json([
                    'authenticated' => true,
                    'api_token' => $user->api_token,
                    'user_id' => $user->id

            ]);

        }

        return response()
            ->json ([
                'email' => 'Provider email does not matched'
            ], 422);
    }

    public function checkAuthentication()
    {
        $boolen = true;
        if(Auth::user()){
            $boolen = true;
        }else{
            $boolen = false;
        }
        return response()->json(['success'=>$boolen], 200);
    }
}
