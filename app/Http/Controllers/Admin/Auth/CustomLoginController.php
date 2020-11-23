<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' =>'required',
            'password' => 'required',
        ]);
        
                //                    لتسجيل دخول لحقل واحد
        $result = Auth::guard('web')->attempt([
            'email' =>$request->email,
            'password' => $request->password
        ]);
        
        if($result){
            return redirect('/');
        }
        
        /*
        $user = User::where('email', $request->email)
        ->orWhere('userName' , $request->userName)
        ->first();

        if($user && Hash::check($request->password , $user->password)){
            Auth::login($user); لحتى اسجلو في السشن انو عامل لوقن 
            return  redirect('/');
        }
        */
        return redirect()->back()
        ->withInput() //ترجع old
        ->with('error' , 'Invalid Email Or Password');
    }
}
