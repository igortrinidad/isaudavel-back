<?php

namespace App\Http\Controllers\Auth;

use App\Models\Client;
use App\Models\Professional;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new user instance after a valid registration.
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {

        //Checar se o profissional já está cadastrado.
        $checkClient = Professional::where('email', $request->get('email'))->first();

        if($checkClient){
            return response()->json([
                'message' => 'Professional already exist.',
                'status' => 422
            ]);
        }

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);

        return Professional::create($request->all());
    }

    /**
     * Create a new user instance after a valid registration.
     * @param Request $request
     * @return mixed
     */
    public function registerClient(Request $request)
    {

        //Checar se o cliente já está cadastrado.
        $checkClient = Client::where('email', $request->get('email'))->first();

        if($checkClient){
            return response()->json([
                'message' => 'Client already exist.',
                'status' => 422
            ]);
        }

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);

        return Client::create($request->all());
    }
}
