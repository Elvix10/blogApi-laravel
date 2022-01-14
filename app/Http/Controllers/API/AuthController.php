<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){

       /* $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user=User::create([

            'name'=>$validatedData['name'],
            'email'=>$validatedData['email'],
            'password'=>$validatedData['password']
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;*/


        /*return response()->json([
            'message' => "User registered successfully",
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);*/
        return response(['message' => 'Register successfully', 'data' => $request->all()]);

    }

    public function login(Request $request){

        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        // check email

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => "User logged in successfully",
        ]);

    }

    public function profile(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
