<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request, JWTAuth $JWTAuth)
    {
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
            'user' =>  $JWTAuth->user()
        ])->header('Authorization','Bearer '. $token);
    }
}
