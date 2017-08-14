<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;

class CheckProfessional
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
        $user = \Auth::user();
        $user = $user ? \Auth::guard('professional')->user() : \Auth::guard('client')->user();

        if ($user->role != 'professional') {
            return response()->json(['error' => 'Forbiden.'], 403);
        }

        //get professional companies
        $professional_companies = $user->companies()->get()->pluck('id')->flatten()->toArray();

        //get client
        $client = Client::find($request->route('id'));

        $client_companies = $client->companies()->get()->pluck('id')->flatten();

        foreach($professional_companies as $company){

            if ($client_companies->contains($company)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Forbiden.'], 403);
    }
}