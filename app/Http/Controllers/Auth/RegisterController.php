<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
use App\User;
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
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userRepository
     * @param ClientRepository $clientRepository
     */
    public function __construct(UserRepository $userRepository, ClientRepository $clientRepository)
    {

        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
    }

    /**
     * Create a new user instance after a valid registration.
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        return $this->userRepository->create($request->all());
    }

    /**
     * Create a new user instance after a valid registration.
     * @param Request $request
     * @return mixed
     */
    public function registerClient(Request $request)
    {
        return $this->clientRepository->create($request->all());
    }
}
