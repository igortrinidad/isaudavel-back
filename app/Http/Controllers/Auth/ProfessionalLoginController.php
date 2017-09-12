<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\JWTAuth;

class ProfessionalLoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request, JWTAuth $JWTAuth)
    {

        $professional_exists = Professional::where('email', $request->get('email'))->first();

        if(!$professional_exists){

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
            'user' =>  $JWTAuth->user()->load('companies', 'categories')
        ])->header('Authorization','Bearer '. $token);
    }

    public function landingLogin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::guard('professional_web')->attempt($credentials)) {

            return redirect()->intended('profissional/dashboard/empresas');

        }else{

            flash('Usuário ou senha inválidos.')->error()->important();

            return redirect()->back()->withInput($request->only(['email']));
        }
    }

    public function logout()
    {
        Auth::guard('professional_web')->logout();

        return redirect()->to('/');
    }
}
