<?php

namespace App\Http\Controllers\Auth;


use App\Models\Client;
use App\Models\ClientPhoto;
use App\Models\ClientSocialProvider;
use App\Models\OracleSocialProvider;
use App\Models\OracleUser;
use App\Models\Professional;
use App\Models\ProfessionalPhoto;
use App\Models\ProfessionalSocialProvider;
use App\Models\User;
use App\Models\UserSocialProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTAuth;
use Socialite;

class SocialAuthController extends Controller
{

    /**
     * @var JWTAuth
     */
    private $JWTAuth;

    /**
     * SocialAuthController constructor.
     * @param JWTAuth $JWTAuth
     */
    function __construct(JWTAuth $JWTAuth)
    {
        $this->JWTAuth = $JWTAuth;
    }

    public function socialLogin(Request $request)
    {

        /*
         * Handling with a client user
         */
        if($request->has('role') && $request->get('role') == 'client'){

            $clientSocialProvider = ClientSocialProvider::where('provider_id', $request->get('id'))->first();

            if(!$clientSocialProvider)
            {
                //Handle user already logged and want log with facebook too
                if($request->has('user_email')){

                    $user = Client::whereEmail($request->get('user_email'))->first();

                    if($user){
                        $user->socialProviders()->create([
                            'provider' => 'facebook',
                            'provider_id' => $request->get('id'),
                            'access_token' => $request->get('access_token'),
                            'photo_url' => $request->get('photo_url')
                        ]);
                    }
                }

                if(!$request->has('user_email')){

                    //Create client
                    $user = Client::firstOrCreate([
                        'name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'email' => $request->get('email')
                    ]);

                    $user->socialProviders()->create([
                        'provider' => 'facebook',
                        'provider_id' => $request->get('id'),
                        'access_token' =>$request->get('access_token'),
                        'photo_url' => $request->get('photo_url')
                    ]);

                    $this->uploadClientAvatar($user->id, $request->get('photo_url'));
                }

            }else{
                $user = $clientSocialProvider->client;
            }
        }

        /*
        * Handling with a professional user
        */
        if($request->has('role') && $request->get('role') == 'professional'){

            $userSocialProvider = ProfessionalSocialProvider::where('provider_id', $request->get('id'))->first();

            if(!$userSocialProvider)
            {
                if($request->has('user_email')){

                    $user = Professional::whereEmail($request->get('user_email'))->first();

                    if($user){
                        $user->socialProviders()->create([
                            'provider' => 'facebook',
                            'provider_id' => $request->get('id'),
                            'access_token' =>$request->get('access_token'),
                            'photo_url' => $request->get('photo_url')
                        ]);

                    }
                }

                if(!$request->has('user_email')){

                    //Create user
                    $user = Professional::firstOrCreate([
                        'name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'email' => $request->get('email')
                    ]);

                    $user->socialProviders()->create([
                        'provider' => 'facebook',
                        'provider_id' => $request->get('id'),
                        'access_token' =>$request->get('access_token'),
                        'photo_url' => $request->get('photo_url')
                    ]);

                    $this->uploadProfessionalAvatar($user->id, $request->get('photo_url'));
                }

            }else{
                $user = $userSocialProvider->professional;
            }
        }

        /*
        * Handling with a oracle user
        */


        if($request->has('role') && $request->get('role') == 'oracle'){

            $userSocialProvider = OracleSocialProvider::where('provider_id', $request->get('id'))->first();

            if(!$userSocialProvider)
            {
                $user = OracleUser::whereEmail($request->get('email'))->first();

                if($user){
                    $user->socialProviders()->create([
                        'provider' => 'facebook',
                        'provider_id' => $request->get('id'),
                        'access_token' =>$request->get('access_token'),
                        'photo_url' => $request->get('photo_url')
                    ]);
                }

            }else{
                $user = $userSocialProvider->user;
            }
        }

        if($user){
            //Verifies if the account belongs to the authenticated user.
            if($request->has('user_id') && $user->id != $request->get('user_id')){
                return response([
                    'status' => 'error',
                    'code' => 'ErrorGettingSocialUser',
                    'msg' => 'Facebook account already in use.'
                ], 400);
            }

            if ( ! $token = $this->JWTAuth->fromUser($user)) {
                throw new AuthorizationException;
            }

            return response([
                'status' => 'success',
                'msg' => 'Successfully logged in via Facebook.',
                'access_token' => $token,
                'user' => $user->load('socialProviders')
            ])->header('Authorization','Bearer '. $token);;
        }

        return response([
            'status' => 'error',
            'code' => 'ErrorGettingSocialUser',
            'msg' => 'Unable to authenticate with Facebook.'
        ], 403);
    }

    function uploadClientAvatar($client_id, $photo_url){

        $fileName = bin2hex(random_bytes(16)) . '.jpg';

        $filePath = 'client/photo/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($photo_url), 'public');

        $data = ['path' => $filePath, 'client_id' => $client_id, 'is_profile' => true, 'is_public' => true];

        return ClientPhoto::create($data);
    }

    function uploadProfessionalAvatar($professional_id, $photo_url){

        $fileName = bin2hex(random_bytes(16)) . '.jpg';

        $filePath = 'client/photo/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($photo_url), 'public');

        $data = ['path' => $filePath, 'professional_id' => $professional_id, 'is_profile' => true];

        return ProfessionalPhoto::create($data);
    }
}