<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        $login = $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        if(!Auth::attempt($login)){
            return response(['message' =>'Ошибка входа']);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        return response(['user'=> Auth::user(), 'accessToken'=>$accessToken]);
    }
}
