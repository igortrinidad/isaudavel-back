<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        foreach (Auth::user()->companies as $company) {
            // redirect if the user owns any company
            if ($company->owner_id == Auth::user()->id || $company->pivot->is_admin) {
                return $next($request);
            }


        }

        flash('Somente proprietários ou administradores de empresas podem realizar este login.')->info()->important();

        return redirect()->back();

    }
}
