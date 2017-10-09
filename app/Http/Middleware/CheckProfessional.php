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

        //handle  client id
        $client_id = $request->get('client_id') ?  $request->get('client_id') : $request->route('id');
        
        if(!$user){
            return response()->json(['error' => 'Forbiden.'], 403);
        }

        \Config::set('auth.defaults.guard', $user->role);

        if ($user->role == 'professional') {

            //get professional companies
            $professional_companies = $user->companies()->get()->pluck('id')->flatten()->toArray();

            //get client
            $client = Client::find($client_id);

            //handle with professional companies
            if ($professional_companies) {
                $client_companies = $client->companies()->get()->pluck('id')->flatten();

                foreach ($professional_companies as $company) {

                    if ($client_companies->contains($company)) {
                        return $next($request);
                    }
                }
            }

            //handle with client professionals
            $client_professionals = $client->professionals()->get()->pluck('id')->flatten();

            if ($client_professionals->contains($user->id)) {
                return $next($request);
            }
        }

        if($user->role == 'client' && $user->id == $client_id){
            return $next($request);
        }

        return response()->json(['error' => 'Forbiden.'], 403);
    }
}
