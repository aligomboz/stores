<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    public function token(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'refresh' => 'boolean',
            'device' => 'required|string',
        ]);
        $user = User::where('name'  , $request->name)->first();
            if($user && Hash::check($request->password , $user->password)){
                if(!$user->api_token || $request->refresh == 1){
                    $token = Str::random(33);
                    $user->api_token = $token;
                    $user->save();
                }
                $token = $user->createToken($request->device , ['products.create']);
                return response()->json([
                    'token' => $user->api_token,
                    'sanctum_token' => $token->plainTextToken,
                ]);
            }
        return response()->json([
            'code' => 0,
            'message' => 'Invalid UserName or Password ?',
        ]);
    }

    public function logout(Request $request)
    {

        $user = $request->user();
        $user->api_token = null;
        $user->save();
        $token =  $user->currentAccessToken();
        $user->tokens()->where('token' , $token->token)->delete();
        $user->currentAccessToken();
        return response()->json([
            'code' => 1,
            'message' => 'LogOut ?',
        ]);
    }
}

 /*
   if($user && Hash::check($request->password , $user->password)){
            $token = Str::random(33);
            $user->api_token = $token;
            $user->save();
            return response()->json([
                'token' => $token,
            ]);
        }*/