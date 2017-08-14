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

        //Sets the guard by according with user role
        \Config::set('auth.defaults.guard', $user->role);

        return $next($request);
    }
}
