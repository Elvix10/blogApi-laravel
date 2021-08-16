<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function register(Request $request){

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user=User::create([

            'name'=>$validatedData['name'],
            'email'=>$validatedData['email'],
            'password'=>$validatedData['password']
        ]);
  
        $token = $user->createToken('auth_token')->plainTextToken;
       

        return response()->json([
            'message' => "User registered successfully",
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);

    }

    public function login(Request $request){

        if(!Auth::attempt($request->only('email','password'))){

            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => "User logged in successfully",
        ]);

    }

    public function logout(Request $request)
    {
        //auth()->user()->tokens()->delete();
        //return response()->json(['message' => 'User successfully signed out']);
    }
}
