<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

/*
 * Auth
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'Auth\ProfessionalLoginController@login');
    Route::post('/signup', 'Auth\RegisterController@register');
    Route::post('/client/signup', 'Auth\RegisterController@registerClient');
    Route::post('/social', 'Auth\SocialAuthController@socialLogin');
});

/*
 * Routes for both roles (client and professional)
 */
Route::group(['middleware' => 'both.auth'], function () {

        Route::get('/tools/evaluation_index/list', 'EvaluationIndexController@index');
        Route::post('/tools/evaluation_index/create', 'EvaluationIndexController@store');


        Route::group(['prefix' => 'event'], function () {

            //Event resources
            Route::post('/store', 'EventController@store');
            Route::post('/update', 'EventController@update');

            //Photo resources
            Route::get('/photo/list/{id}', 'EventPhotoController@index');
            Route::post('/photo/upload', 'EventPhotoController@store');
            Route::post('/photo/update', 'EventPhotoController@update');
            Route::get('/photo/destroy/{id}', 'EventPhotoController@destroy');

            //Participation
            Route::post('/participant/confirm', 'EventParticipantController@confirm');
            Route::post('/participant/cancel', 'EventParticipantController@cancel');
            Route::post('/participant/check_presence', 'EventParticipantController@check_presence');

            //Comment
            Route::post('/comment/store', 'EventCommentController@store');
            Route::post('/comment/destroy', 'EventCommentController@destroy');
        });

        //Protected  meal recipe resources
        Route::group(['prefix' => 'meal_recipe'], function () {

            //Meal types
            Route::get('/type/list', 'MealTypeController@forSelect');

            //Meal recipes resources
            Route::get('/show/{id}', 'MealRecipeController@show');
            Route::post('/store', 'MealRecipeController@store');
            Route::post('/update', 'MealRecipeController@update');
            Route::post('/destroy', 'MealRecipeController@destroy');

            //Meal recipes photos resources
            Route::post('/photo/upload', 'MealRecipePhotoController@store');
            Route::get('/photo/destroy/{id}', 'MealRecipePhotoController@destroy');

            //Meal recipes resources
            Route::get('/tag/list', 'MealRecipeTagController@forSelect');
            Route::post('/tag/store', 'MealRecipeTagController@store');

            //comment
            Route::post('/comment/store', 'MealRecipeCommentController@store');
            Route::get('/comment/destroy/{id}', 'MealRecipeCommentController@destroy');

            //rating
            Route::post('/rating/store', 'MealRecipeRatingController@store');
        });

});


/*
* Unprotected Company Router
*/
Route::group(['prefix' => 'company'], function(){
    Route::get('/show/{slug}', 'CompanyController@show_public');
    Route::post('/search/location', 'CompanyController@searchByLocation');
    Route::post('/search/category', 'CompanyController@searchByCategory');
    Route::get('/category/list', 'CategoryController@forSelect');
    Route::get('/recomendation/received/{id}', 'RecomendationController@receivedList');
});

/*
* Unprotected Company Router
*/
Route::group(['prefix' => 'client_public'], function(){
    Route::get('/show/{id}', 'ClientController@show_public');
    Route::get('/activity/list/{id}', 'ActivityController@client_list_public');
});

/*
* Unprotected Professional Router
*/
Route::group(['prefix' => 'professional'], function(){
    Route::get('/recomendation/received/{id}', 'RecomendationController@receivedList');
    Route::get('/recomendation/sent/{id}', 'RecomendationController@sentList');
});

/*
* Unprotected Event Router
*/
Route::get('event/show/{id}', 'EventController@show');
Route::post('event/list', 'EventController@index');
Route::post('event/home/list', 'EventController@homeList');
Route::get('event/comment/list/{id}', 'EventCommentController@index');
Route::get('event/participant/list/{id}', 'EventParticipantController@index');

/*
* Unprotected Check Slug
*/
Route::get('check_slug/company/{slug}', 'CompanyController@check_slug');
Route::get('check_slug/client/{slug}', 'ClientController@check_slug');
Route::get('check_slug/professional/{slug}', 'ProfessionalController@check_slug');
Route::get('check_slug/event/{slug}', 'EventController@check_slug');

//Modality routes

Route::get('/modality/list', 'ModalityController@forSelect');

/*
* Unprotected Router
*/
Route::group(['prefix' => 'tools'], function(){

    //Generate new Pass
    Route::get('users/generateNewPass/professional/{email}', 'ProfessionalController@generateNewPass');
    Route::get('users/generateNewPass/client/{email}', 'ClientController@generateNewPass');
    Route::get('users/generateNewPass/oracle/{email}', 'OracleUserController@generateNewPass');

    //Last production version of the app
    Route::get('system/get_last_production_version', 'SystemController@get_last_production_version');

    //Tracking
    Route::post('information/collect', 'LeadTrackingController@store');

});

/*
 * Unprotected meals routes
 */
Route::group(['prefix' => 'meal'], function(){

    Route::get('/type/list', 'MealTypeController@forSelect');
    Route::get('/tag/list', 'MealRecipeTagController@forSelect');
    Route::post('/recipe/list', 'MealRecipeController@index');
    Route::post('/recipe/home/list', 'MealRecipeController@homeList');

    Route::post('/recipe/search', 'MealRecipeController@searchByTitle');
    Route::post('/recipe/filter/nutrients', 'MealRecipeController@filterNutrients');
    Route::post('/recipe/type/search', 'MealRecipeController@searchByType');
    Route::post('/recipe/tag/search', 'MealRecipeController@searchByTag');
    Route::get('/recipe/comment/list/{id}', 'MealRecipeCommentController@index');
    Route::get('/recipe/rating/list/{id}', 'MealRecipeRatingController@index');
    Route::get('/recipe/show/{slug}', 'MealRecipeController@showPublic');
    Route::get('/recipe/from/{id}', 'MealRecipeController@recipesByUser');
});


/*
 * Unprotected Site Articles
 */
Route::group(['prefix' => 'article'], function(){

    Route::get('/last/{quantity}', 'SiteArticleController@last_articles_for_app');
    Route::get('/list_random/{quantity}', 'SiteArticleController@list_random_articles_for_app');
    Route::get('/show/{slug}', 'SiteArticleController@show_for_app');

});

/*
 * Oracle
 */

Route::group(['prefix' => 'oracle'], function () {

    Route::post('/fcm_token', 'OracleUserController@fcmToken');
    Route::post('/status', 'OracleUserController@status');


    //Notifications
    Route::group(['prefix' => 'notification'], function() {
        Route::get('/mark_readed/{id}', 'OracleNotificationController@markReaded');
        Route::post('/mark_all_readed', 'OracleNotificationController@markAllReaded');
    });


    //Notifications
    Route::group(['prefix' => '/sales/dashboard'], function() {
        Route::post('/data', 'SalesController@dashboardData');
    });



    //Hubspot Webhook
    Route::group(['prefix' => 'hubspot'], function() {

        //Get the APP auth code from hubspot
        Route::get('/webhook/get_code', function(Request $request){

            $code  = $request->get('code');

            if(!$code){
                return response()->json(['message' => 'Integration error: No authorization code provided.'], 500);
            }

            \Redis::set('hubspot_app_auth_code', $code);

            //Get the access and refresh tokens via CURL
            $service_url = 'https://api.hubapi.com/oauth/v1/token';
            $curl = curl_init($service_url);
            $curl_post_data = array(
                'grant_type' => 'authorization_code',
                'client_id' => env('HUBSPOT_APP_CLIENT_ID'),
                'client_secret' => env('HUBSPOT_APP_CLIENT_SECRET'),
                'redirect_uri' => env('HUBSPOT_APP_REDIRECT_URI'),
                'code' => $code
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post_data));

            $curl_response = json_decode(curl_exec($curl), true);

            \Redis::set('hubspot_app_access_token', $curl_response['access_token']);
            \Redis::set('hubspot_app_refresh_token', $curl_response['refresh_token']);

            return response()->json(['message' => 'Successfully integrated with HubSpot']);
        });


        //Receive subscription events from Hubspot
        Route::post('/webhook/receive', function(Request $request){
            
            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = '<h4>TESTE WEBHOOK</h4>';
            $data['messageOne'] = 'Webhook action received';

            $data['messageSubject'] = 'Cadastro iSaudavel';

            \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
                $message->from('no-reply@isaudavel.com', 'iSaudavel App');
                $message->to('contato@maisbartenders.com.br', 'Igor')->subject($data['messageSubject']);
                $message->cc('me@matheuslima.com.br', 'Matheus')->subject($data['messageSubject']);
            });

            return response()->json(['message' => 'success']);

        });

    });

});


Route::get('/fcm_test', function(){


    $optionBuilder = new \LaravelFCM\Message\OptionsBuilder();
    $optionBuilder->setTimeToLive(60*20);

    $notificationBuilder = new \LaravelFCM\Message\PayloadNotificationBuilder();
    $notificationBuilder->setTitle('iSaudavel Oracle')
        ->setBody('Hello from Firebase')
        ->setSound('default')
        ->setIcon('https://app.isaudavel.com/static/assets/img/icons/icon_g.png')
        ->setClickAction('FCM_PLUGIN_ACTIVITY');

    $dataBuilder = new \LaravelFCM\Message\PayloadDataBuilder();
    $dataBuilder->addData(['icon' => 'https://app.isaudavel.com/static/assets/img/icons/icon_g.png']);
    $dataBuilder->addData(['title' => 'iSaudavel Oracle']);
    $dataBuilder->addData(['content' => 'Hello from Firebase']);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

    $token = "cb-dQHtp0Hs:APA91bFDfLemmMg3LwJSMR6POsUW1MDWTjRZoWuX9sAN1OtGYEJZzXwZOieVCZoiN0oh_O9PmVDmqlGVpDer2SomFZnIVxiFL6FR6mRC7sDEMhXVs0_gZIlnBooBnmtaYQRolAqRc0ir";
    $downstreamResponse = \FCM::sendTo($token, $option, $notification, $data);

    dd($downstreamResponse);




});

Route::get('/hubspot/test', function(){




    $contacs = \HubSpot::contacts()->statistics();

    return response()->json($contacs);

});

