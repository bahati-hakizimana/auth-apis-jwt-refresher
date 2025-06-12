<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use  App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request){

      $request ->validate([
        "name" => "required",
        "email" => "required|email|unique:users",
        "password" => "required|confirmed"
      ]);

    //   Save user

    User::create([
        "name" =>$request->name,
        "email" => $request->email,
        "password" =>Hash::make($request->password)
    ]);

    return response()->json([
        "status" =>true,
        "message" => "User created successfully"
    ]);


        

    }

    public function login(Request $request){

        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){

            return response()->json([
                "status" =>true,
                "message" => "Loged in successfully",
                "token" => $token
            ]);
        }

        return response()->json([
            "status" =>false,
            "Message" => "Invalid user credentials",
        ]);
         
    }

    public function profile(){

        $userData = auth()->user();
        return response()->json([
            "status" =>true,
            "message" => "profile data fetched successfully",
            "userdata" => $userData
        ]);

    }
    public function logout(){

        auth()->logout();
        return response()->json([
            "status" =>true,
            "message" => "Lopggedout successfully"
        ]);

    }

    public function refreshToken(){

        $newToken = auth()->refresh();
        return response()->json([
            "status" => true,
            "refreshToken" => $newToken
        ]);

    }
}
