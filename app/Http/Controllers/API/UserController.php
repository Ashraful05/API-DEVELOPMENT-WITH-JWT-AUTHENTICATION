<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //validate data....
        $this->validate($request,[
           'name'=>'required',
           'email'=>'required|email|unique:users',
           'phone_no'=>'required|unique:users',
           'password'=>'required|confirmed'
        ]);
        //create user data...
        $user = new User();
        $user->name = $request->name;
        $user->email=$request->email;
        $user->phone_no= $request->phone_no;
//        $user->password = bcrypt($request->password);
        $user->password = Hash::make($request->password);
        //save..
        $user->save();
        //send response.....
        return response()->json([
           'status'=>1,
           'message'=>'User Registered Successfully!!!'
        ],200);
    }
    public function login(Request $request)
    {
        //validation......
        $this->validate($request,[
           'email' => 'required|email',
            'password'=>'required'
        ]);

        //verify user + token.....
        $token = auth()->attempt([
           'email' => $request->email,
           'password' => $request->password
        ],);
        if(!$token)
        {
            return response()->json([
               'status'=> 0,
                'message'=>'Invalid Credentials!!!!'
            ],404);
        }

        //send response......

        return response()->json([
           'status'=>1,
            'message'=>'Logged in Successfully!!!!',
            'access_token'=> $token
        ]);
    }
    public function profile()
    {
        $userData = auth()->user();
        return response()->json([
           'status' => 1,
           'message' => 'User Profile Data',
            'data' => $userData
        ]);

    }
    public function logout()
    {
        auth()->logout();
        return response()->json([
           'status'=>1,
           'message' => 'user logged out successfully!!!',

        ]);
    }
}
