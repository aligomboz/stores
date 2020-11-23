<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $maxAttempts = 3;
    protected $decayMinutes = 2;
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }

    protected function redirectTo(){
        /*
        $user = $this->guard('web')->user();
        if($user->type != 'user'){
             return '/admin/category';
        }
        return RouteServiceProvider::HOME;
        */
        return route('frontPage');
    }

   
    public function guard()
    {
        return Auth::guard('web');//تعمل كستم لديفلت
    }
    
    protected function loggedOut($request)
    {
        return redirect($this->redirectTo());
    }
    
}
