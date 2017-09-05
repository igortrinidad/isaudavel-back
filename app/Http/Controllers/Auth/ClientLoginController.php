<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\JWTAuth;

class ClientLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Get the guard to be used during authentication.
     * Overrides default Laravel method to get the 'api' guard
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard('client');
    }

    public function login(Request $request)
    {
        $client_exists = Client::where('email', $request->get('email'))->first();

        if(!$client_exists){

            return response()->json(['user_not_found' => true, 'message' => ' Não localizamos seu usuário.'], 200);
        }


        $credentials = $request->only(['email', 'password']);
        try {

            $token = $this->guard()->attempt($credentials);

            if(!$token) {
                throw new AccessDeniedHttpException('Invalid credentials');
            }
        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        return response()->json([
            'access_token' => $token,
            'user' =>  $this->guard()->user()->load('socialProviders')
        ])->header('Authorization','Bearer '. $token);
    }
}
