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
 * Professional
 */
Route::group(['prefix' => 'professional'], function () {

    //Professional protected routes
    Route::group(['middleware' => 'auth:professional'], function () {

        //Categories
        Route::get('/category/list', 'CategoryController@forSelect');

        //Company
        Route::group(['prefix' => 'company'], function(){
            
            //Company resources
            Route::get('/my_companies', 'CompanyController@professionalCompanies');
            Route::post('/create', 'CompanyController@store');
            Route::post('/update', 'CompanyController@update');
            Route::get('/show/{id}', 'CompanyController@show');
            Route::get('/destroy/{id}', 'CompanyController@destroy');

            //Photo resources
            Route::get('/photo/list/{id}', 'CompanyPhotosController@index');
            Route::post('/photo/upload', 'CompanyPhotosController@store');
            Route::post('/photo/update', 'CompanyPhotosController@update');
            Route::get('/photo/destroy/{id}', 'CompanyPhotosController@destroy');

        });


        //Photo resources
        Route::get('/photo/list', 'ProfessionalPhotoController@index');
        Route::post('/photo/upload', 'ProfessionalPhotoController@store');
        Route::post('/photo/update', 'ProfessionalPhotoController@update');
        Route::get('/photo/destroy/{id}', 'ProfessionalPhotoController@destroy');

        //Activity resources
        Route::post('/activity/create', 'ActivityController@store');
        Route::post('/activity/update', 'ActivityController@update');
        Route::get('/activity/destroy/{id}', 'ActivityController@destroy');

        //trainning resources
        Route::get('/trainning/list/{id}', 'TrainningController@index');
        Route::get('/trainning/list/destroyeds/{id}', 'TrainningController@listdestroyeds');
        Route::post('/trainning/create', 'TrainningController@store');
        Route::post('/trainning/update', 'TrainningController@update');
        Route::get('/trainning/destroy/{id}', 'TrainningController@destroy');
        Route::get('/trainning/undestroy/{id}', 'TrainningController@undestroy');

        //diet resources
        Route::get('/diet/list/{id}', 'DietController@index');
        Route::get('/diet/list/destroyeds/{id}', 'DietController@listdestroyeds');
        Route::post('/diet/create', 'DietController@store');
        Route::post('/diet/update', 'DietController@update');
        Route::get('/diet/destroy/{id}', 'DietController@destroy');

        //Evaluation resources
        Route::post('/evaluation/create', 'EvaluationController@store');
        Route::post('/evaluation/update', 'EvaluationController@update');
        Route::get('/evaluation/destroy/{id}', 'EvaluationController@destroy');

        //Evaluation photos
        Route::post('/evaluation/photo/upload', 'EvaluationPhotoController@store');
        Route::get('/evaluation/photo/destroy/{id}', 'EvaluationPhotoController@destroy');

        //restrictions resources
        Route::post('/restriction/create', 'RestrictionController@store');
        Route::post('/restriction/update', 'RestrictionController@update');
        Route::get('/restriction/destroy/{id}', 'RestrictionController@destroy');


        //Exam resources
        Route::get('/exam/list/{id}', 'ExamController@index');
        Route::post('/exam/create', 'ExamController@store');
        Route::post('/exam/update', 'ExamController@update');
        Route::get('/exam/destroy/{id}', 'ExamController@destroy');

        //Exam attachments
        Route::post('/exam/attachment/upload', 'ExamAttachmentController@store');
        Route::get('/exam/attachment/destroy/{id}', 'ExamAttachmentController@destroy');

        //profile update
        Route::get('/profile/show/{id}', 'ProfessionalController@show');
        Route::post('/profile/update', 'ProfessionalController@update');
    });

    //Open routes
    Route::post('/category/search', 'ProfessionalController@searchByCategory');
});


/*
 * Clients
 */
Route::group(['prefix' => 'client'], function () {
    Route::post('/auth/login', 'Auth\ClientLoginController@login');

    //Client protected routes
    Route::group(['middleware' => 'auth:client'], function () {

        //Photo resources
        Route::get('/photo/list', 'ClientPhotoController@index');
        Route::post('/photo/upload', 'ClientPhotoController@store');
        Route::post('/photo/update', 'ClientPhotoController@update');
        Route::get('/photo/destroy/{id}', 'ClientPhotoController@destroy');

        //activity resources
        Route::get('/activity/list', 'ActivityController@index');
        Route::post('/activity/create', 'ActivityController@store');
        Route::post('/activity/update', 'ActivityController@update');
        Route::get('/activity/destroy/{id}', 'ActivityController@destroy');

        //trainning resources
        Route::get('/trainning/list/{id}', 'TrainningController@index');
        Route::get('/trainning/list/destroyeds/{id}', 'TrainningController@listdestroyeds');
        Route::post('/trainning/create', 'TrainningController@store');
        Route::post('/trainning/update', 'TrainningController@update');
        Route::get('/trainning/destroy/{id}', 'TrainningController@destroy');
        Route::get('/trainning/undestroy/{id}', 'TrainningController@undestroy');

        //diet resources
        Route::get('/diet/list/{id}', 'DietController@index');
        Route::get('/diet/list/destroyeds/{id}', 'DietController@listdestroyeds');
        Route::post('/diet/create', 'DietController@store');
        Route::post('/diet/update', 'DietController@update');
        Route::get('/diet/destroy/{id}', 'DietController@destroy');
        Route::get('/diet/undestroy/{id}', 'DietController@undestroy');

        //restrictions resources
        Route::get('/restriction/list/{id}', 'RestrictionController@index');
        Route::post('/restriction/create', 'RestrictionController@store');
        Route::post('/restriction/update', 'RestrictionController@update');
        Route::get('/restriction/destroy/{id}', 'RestrictionController@destroy');

        //Exam resources
        Route::get('/exam/list/{id}', 'ExamController@index');
        Route::post('/exam/create', 'ExamController@store');
        Route::post('/exam/update', 'ExamController@update');
        Route::get('/exam/destroy/{id}', 'ExamController@destroy');

        //Exam attachments
        Route::post('/exam/attachment/upload', 'ExamAttachmentController@store');
        Route::get('/exam/attachment/destroy/{id}', 'ExamAttachmentController@destroy');

        //evaluation resources
        Route::get('/evaluation/list/{id}', 'EvaluationController@index');

        //profile update
        Route::post('/profile/update', 'ClientController@update');
    });
});

/*
 * Oracle
 */
Route::group(['prefix' => 'oracle'], function () {
    Route::post('/auth/login', 'Auth\OracleLoginController@login');

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

/*
* Unprotected Router
*/
Route::group(['prefix' => 'company'], function(){
    Route::post('/search/location', 'CompanyController@searchByLocation');
    Route::post('/search/category', 'CompanyController@searchByCategory');
});


/*
* Unprotected Router
*/
Route::group(['prefix' => 'tools'], function(){

    //Generate new Pass
    Route::get('users/generateNewPass/professional/{email}', 'ProfessionalController@generateNewPass');
    Route::get('users/generateNewPass/client/{email}', 'ClientController@generateNewPass');
    Route::get('users/generateNewPass/oracle/{email}', 'OracleUserController@generateNewPass');
});


Route::post('geo_test', function (Request $request) {

    $user_lat = $request->get('lat');
    $user_lng = $request->get('lng');

    $companies = \App\Models\Company::select(\DB::raw("*, 
                (ATAN(SQRT(POW(COS(RADIANS(companies.lat)) * SIN(RADIANS(companies.lng)
                 - RADIANS('$user_lng')), 2) +POW(COS(RADIANS('$user_lat')) * 
                 SIN(RADIANS(companies.lat)) - SIN(RADIANS('$user_lat')) * cos(RADIANS(companies.lat)) * 
                 cos(RADIANS(companies.lng) - RADIANS('$user_lng')), 2)),SIN(RADIANS('$user_lat')) * 
                 SIN(RADIANS(companies.lat)) + COS(RADIANS('$user_lat')) * COS(RADIANS(companies.lat)) * 
                 COS(RADIANS(companies.lng) - RADIANS('$user_lng'))) * 6371000) as distance_m"))
                    ->with(['professionals' => function($query){
                        $query->select('id', 'name', 'last_name')
                            ->with(['categories' => function($query){
                            $query->select('name');
                        }])->orderBy('name', 'asc');
                    }])
                    ->get();


    //format response
    $nearby_companies = $companies->map(function ($item, $key) {

        // meter to km
        $distance_km = round(($item->distance_m / 1000) , 2);

        $item = collect($item);

        //add fields on item
        $item->put('distance_km', $distance_km);

        return $item->all();
    });

    return response()->json($nearby_companies->all());
});
