<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$type)
    {
        $user= $request->user();        // Or $user = Auth::user();
        if(!in_array($user->type , $type)){
          return  abort(403,'You are not ' . implode( ', ', $type));
        }
        return $next($request);
    }
}
