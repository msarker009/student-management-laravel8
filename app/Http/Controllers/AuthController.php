<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        //return $request->all();
        $validate=Validator::make($request->all(),[
            'email'=>'required|email|exists:users',
            'password'=>'required'
        ]);

        if ($validate->fails()){
            return response()->json(['errors'=>$validate->errors()],422);
        }

        $reqData=request()->only('email','password');
        if (Auth::attempt($reqData)){
            $user=Auth::user();
            $data['access_token'] = $user->createToken('userToken')->accessToken;
            $data['user']=$user;
            return response()->json($data,200);

        }else{
            return response()->json(['loginFailed'=>'Email or Password Incorrect'],401);
        }
    }

    public function register(Request $request){
        //return $request->all();
        $validate=Validator::make($request->all(),[
            'name'=>'required|min:4',
            'email'=>'required|email|unique:users',
            'phone'=>'required|max:14|unique:users',
            'password'=>'required|min:4|confirmed'
        ]);

        if ($validate->fails()){
            return response()->json(['errors'=>$validate->errors()],422);
        }

        $reqData=request()->only('name','email','phone','password');
        $reqData['password']=Hash::make($request->password);
        $user=User::create($reqData);
        Auth::login($user);
        $data['access_token'] = $user->createToken('userToken')->accessToken;
        $data['user']=$user;
        return response()->json($data,200);


    }
    public function logout(){
        Auth::user()->token()->revoke();
        return response()->json(['message'=>'User Successfully Logout']);
    }


}
