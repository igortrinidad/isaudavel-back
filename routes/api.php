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
/*
Route::group(['prefix' => 'oracle'], function () {
    Route::post('/auth/login', 'Auth\OracleLoginController@login');

    Route::post('/tools/send_log_erro', 'SystemController@send_log_erro');

    //Oracle protected routes
    Route::group(['middleware' => 'auth:oracle'], function () {

        //Users
        Route::group(['prefix' => 'users'], function(){
            //List
            Route::get('/professional', 'ProfessionalController@index');
            Route::get('/client', 'ClientController@index');
            Route::get('/oracle', 'OracleUserController@index');

            //Show
            Route::get('/show/professional/{id}', 'ProfessionalController@show');
            Route::get('/show/client/{id}', 'ClientController@show');
            Route::get('/show/oracle/{id}', 'OracleUserController@show');

            //Store
            Route::post('/store/professional', 'ProfessionalController@store');
            Route::post('/store/client', 'ClientController@store');
            Route::post('/store/oracle', 'OracleUserController@store');

            //Update
            Route::post('/update/professional', 'ProfessionalController@update');
            Route::post('/update/client', 'ClientController@update');
            Route::post('/update/oracle', 'OracleUserController@update');

            //Destroy
            Route::get('/destroy/professional/{id}', 'ProfessionalController@destroy');
            Route::get('/destroy/client/{id}', 'ClientController@destroy');
            Route::get('/destroy/oracle/{id}', 'OracleUserController@destroy');

            //Generate new Pass
            Route::get('/generateNewPass/professional/{email}', 'ProfessionalController@generateNewPass');
            Route::get('/generateNewPass/client/{email}', 'ClientController@generateNewPass');
            Route::get('/generateNewPass/oracle/{email}', 'OracleUserController@generateNewPass');
        });            

        //profile update
        Route::post('/user/update', 'OracleUserController@update');
    });
});
*/

Route::get('/fcm_test', function(){


    $optionBuilder = new \LaravelFCM\Message\OptionsBuilder();
    $optionBuilder->setTimeToLive(60*20);

    $notificationBuilder = new \LaravelFCM\Message\PayloadNotificationBuilder();
    $notificationBuilder->setTitle('Novo agendamento')
        ->setBody('Você tem um novo agendamento')
        ->setSound('default')
        ->setClickAction('FCM_PLUGIN_ACTIVITY');

    $dataBuilder = new \LaravelFCM\Message\PayloadDataBuilder();
    $dataBuilder->addData(['content' => 'Você tem um novo agendamento']);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

    $token = "d2h9okdqyt4:APA91bGnwaCTQL-nMTiJctNgCxeTYyAzzDITKxrodoCpHuAnODZn9x10rguZEvpWRvu4n73ObT3zTLg9qxUWNsrIEgJFJ35fi9cgHEdF7Q_-nr6rQRRYuEYHfDJxzp7tDZ722i6JTSSJ";
    $downstreamResponse = \FCM::sendTo($token, $option, $notification, $data);

    dd($downstreamResponse);




});
