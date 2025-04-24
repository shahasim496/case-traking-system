<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(Auth::check()){

            $user = Auth::user();
            if($user->is_blocked == 0){
                return $next($request);
            }

           Auth::logout();
           return redirect('/login')->with('error', 'Please contact Admin for login.');
        }

    }
}
