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
        Route::get('/data', 'SalesController@dashboardData');
    });



    //Hubspot Webhook
    Route::group(['prefix' => 'hubspot'], function() {
        Route::get('/contact_created', function(Request $request){
            

            //Envia email para informar o cliente do cadastro
            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = '<h4>TESTE WEBHOOK</h4>';
            $data['messageOne'] = $request->get('portalId');

            $data['messageSubject'] = 'Cadastro iSaudavel';

            \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
                $message->from('no-reply@isaudavel.com', 'iSaudavel App');
                $message->to('contato@maisbartenders.com.br', 'Igor')->subject($data['messageSubject']);
            });

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


   /* $engagementsEndpoint = 'https://api.hubapi.com/engagements/v1/engagements/recent/modified';

    $contacs = hubspot_support($engagementsEndpoint, ['count' =>  5]);

    return response()->json($contacs->data->results);*/

   $owners = HubSpot::owners()->all();

    return response()->json($owners);

});

