<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request){

        $start = microtime(true);

        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|min:6',
            'role'=>'required|string'
        ]);

        $user= User::create([
            'id'=>Str::uuid()->toString(),
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role
        ]);


        event(new Registered($user));


        // إنشاء التوكين
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token],201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json(['message'=>'invalid login details',401]);
        }

        $user= $request->user();
        $token= $user->createToken('token')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token]);
    }

    public function getUser(Request $request){
        // $user = Auth::user();
        $user = $request->user();
        return response()->json($user);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
