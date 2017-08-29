<?php

namespace App\Http\Middleware;

use Closure;

class AuthBoth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();

        $user = $user ? \Auth::guard('professional')->user() : \Auth::guard('client')->user();

        if(!$user){
            return response()->json(['error' => 'Forbiden.'], 403);
        }

        //Sets the guard by according with user role
        \Config::set('auth.defaults.guard', $user->role);

        return $next($request);
    }
}
