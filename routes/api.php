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

            //Plan resources
            Route::get('/plan/list/{id}', 'PlanController@index');
            Route::get('/plan/list/destroyed/{id}', 'PlanController@listDestroyed');
            Route::post('/plan/store', 'PlanController@store');
            Route::post('/plan/update', 'PlanController@update');
            Route::get('/plan/show/{id}', 'PlanController@show');
            Route::get('/plan/destroy/{id}', 'PlanController@destroy');
            Route::get('/plan/undestroy/{id}', 'PlanController@undestroy');

            //Photo resources
            Route::get('/photo/list/{id}', 'CompanyPhotosController@index');
            Route::post('/photo/upload', 'CompanyPhotosController@store');
            Route::post('/photo/update', 'CompanyPhotosController@update');
            Route::get('/photo/destroy/{id}', 'CompanyPhotosController@destroy');

            //Rating
            Route::get('/rating/list/{id}', 'CompanyRatingController@index');

            //Client
            Route::post('/clients', 'ClientController@companyClients');
            Route::post('/professionals', 'ProfessionalController@companyProfessionals');

            //Client
            Route::group(['prefix' => 'client'], function(){

                Route::post('/show', 'ClientController@showCompany');
                Route::post('/create', 'ClientController@store');
                Route::post('/search', 'ClientController@search');
                Route::post('/solicitation', 'ClientController@companySolicitation');
                Route::post('/remove_solicitation', 'ClientController@removeCompanySolicitation');
                Route::post('/accept_solicitation', 'ClientController@acceptCompanySolicitation');
                Route::post('/reactivate_solicitation', 'ClientController@reactivateCompanyRelationship');

            });

            //Subscription
            Route::group(['prefix' => 'subscription'], function(){

                Route::post('/store', 'ClientSubscriptionController@store');
                Route::post('/update', 'ClientSubscriptionController@update');
                Route::post('/index', 'ClientSubscriptionController@index');
                Route::post('/destroy', 'ClientSubscriptionController@destroy');
            });

            //Professional
            Route::group(['prefix' => 'professional'], function(){

                Route::post('/create', 'ProfessionalController@store');
                Route::post('/search', 'ProfessionalController@search');
                Route::post('/solicitation', 'ProfessionalController@companySolicitation');
                Route::post('/accept_solicitation', 'ProfessionalController@acceptCompanySolicitation');
                Route::post('/remove_solicitation', 'ProfessionalController@removeCompanySolicitation');
                Route::post('/updateProfessionalCompanyRelationship', 'ProfessionalController@updateProfessionalCompanyRelationship');

            });

            // Category calendar settings
            Route::post('/category/calendar_settings', 'CategoryCalendarSettingController@show');
            Route::post('/category/calendar_settings/update', 'CategoryCalendarSettingController@update');


            //Professional calendar settings
            Route::post('/professional/calendar_settings/list', 'ProfessionalCalendarSettingController@index');
            Route::post('/professional/calendar_settings/to_reschedule', 'ProfessionalCalendarSettingController@toReschedule');
            Route::post('/professional/calendar_settings', 'ProfessionalCalendarSettingController@show');
            Route::post('/professional/calendar_settings/update', 'ProfessionalCalendarSettingController@update');


            //Invoice
            Route::group(['prefix' => 'invoice'], function(){
                Route::post('/list', 'InvoiceController@index');
                Route::post('/store', 'InvoiceController@store');
                Route::post('/update', 'InvoiceController@update');
                Route::get('/schedules/{id}', 'ScheduleController@byInvoice');
            });

            //Schedule
            Route::post('/schedule/calendar_list', 'ScheduleController@forCalendar');
            Route::post('/schedule/reschedule', 'ScheduleController@reschedule');
            Route::post('/schedule/confirm', 'ScheduleController@confirm');
            Route::post('/schedule/cancel', 'ScheduleController@cancel');
            Route::post('/schedule/update', 'ScheduleController@update');

        });

        //Certifications
        Route::get('/certification/list/{id}', 'CertificationController@index');
        Route::post('/certification/store', 'CertificationController@store');
        //Route::post('/certification/update', 'CertificationController@update');
        Route::get('/certification/destroy/{id}', 'CertificationController@destroy');

        //Photo resources
        Route::get('/photo/list/{id}', 'ProfessionalPhotoController@index');
        Route::post('/photo/upload', 'ProfessionalPhotoController@store');
        Route::post('/photo/update', 'ProfessionalPhotoController@update');
        Route::get('/photo/destroy/{id}', 'ProfessionalPhotoController@destroy');
        Route::post('/photo/set_profile', 'ProfessionalPhotoController@set_profile');

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
        Route::get('/restriction/list/{id}', 'RestrictionController@index');
        Route::get('/restriction/list/destroyeds/{id}', 'RestrictionController@listdestroyeds');
        Route::post('/restriction/create', 'RestrictionController@store');
        Route::post('/restriction/update', 'RestrictionController@update');
        Route::get('/restriction/destroy/{id}', 'RestrictionController@destroy');
        Route::get('/restriction/undestroy/{id}', 'RestrictionController@undestroy');


        //Exam resources
        Route::get('/exam/list/{id}', 'ExamController@index');
        Route::post('/exam/create', 'ExamController@store');
        Route::post('/exam/update', 'ExamController@update');
        Route::get('/exam/destroy/{id}', 'ExamController@destroy');
        //TEST
        Route::post('/exam/storetwo', 'ExamController@storetwo');

        //Exam attachments
        Route::post('/exam/attachment/upload', 'ExamAttachmentController@store');
        Route::get('/exam/attachment/destroy/{id}', 'ExamAttachmentController@destroy');

        //Professional Rating
        Route::get('/rating/list/{id}', 'ProfessionalRatingController@index');

        //Recomendations
        Route::get('/recomendation/received/{id}', 'RecomendationController@receivedList');
        Route::get('/recomendation/sent/{id}', 'RecomendationController@sentList');
        Route::post('/recomendation/create', 'RecomendationController@store');
        Route::post('/recomendation/update', 'RecomendationController@update');
        Route::get('/recomendation/destroy/{id}', 'RecomendationController@destroy');

        //calendar
        Route::post('/calendar/list', 'ScheduleController@professionalCalendar');
        Route::post('/calendar/reschedule', 'ScheduleController@reschedule');

        //profile update
        Route::get('/profile/show/{id}', 'ProfessionalController@show');
        Route::post('/profile/update', 'ProfessionalController@update');
    });

    Route::get('/show/{id}', 'ProfessionalController@show');

    //Open routes
    Route::post('/category/search', 'ProfessionalController@searchByCategory');
});

/*
 * Clients
 */
Route::group(['prefix' => 'client'], function () {
    Route::post('/auth/login', 'Auth\ClientLoginController@login');

    //test client professional middleware


    ////SHARED
    //Shared between professional and client
    Route::group(['middleware' => 'check.professional'], function () {

        //Client
        Route::post('/show', 'ClientController@show');

        //Photo resources
        Route::post('/photo/list', 'ClientPhotoController@index');
        Route::post('/photo/upload', 'ClientPhotoController@store');
        Route::post('/photo/update', 'ClientPhotoController@update');
        Route::get('/photo/destroy/{id}', 'ClientPhotoController@destroy');
        Route::post('/photo/set_profile', 'ClientPhotoController@set_profile');

         //activity resources
        Route::get('/activity/list', 'ActivityController@index');

        //trainning resources OK
        Route::post('/trainning/list', 'TrainningController@index');
        Route::post('/trainning/list/destroyeds', 'TrainningController@listdestroyeds');
        Route::post('/trainning/create', 'TrainningController@store');
        Route::post('/trainning/update', 'TrainningController@update');
        Route::post('/trainning/destroy', 'TrainningController@destroy');
        Route::post('/trainning/undestroy', 'TrainningController@undestroy');

        //diet resources
        Route::post('/diet/list', 'DietController@index');
        Route::post('/diet/list/destroyeds', 'DietController@listdestroyeds');
        Route::post('/diet/create', 'DietController@store');
        Route::post('/diet/update', 'DietController@update');
        Route::post('/diet/destroy', 'DietController@destroy');
        Route::post('/diet/undestroy', 'DietController@undestroy');

        //restrictions resources OK
        Route::post('/restriction/list', 'RestrictionController@index');
        Route::post('/restriction/list/destroyeds', 'RestrictionController@listdestroyeds');
        Route::post('/restriction/create', 'RestrictionController@store');
        Route::post('/restriction/update', 'RestrictionController@update');
        Route::post('/restriction/destroy', 'RestrictionController@destroy');
        Route::post('/restriction/undestroy', 'RestrictionController@undestroy');

        //Exam resources
        Route::post('/exam/list', 'ExamController@index');
        Route::post('/exam/store', 'ExamController@store');
        Route::post('/exam/update', 'ExamController@update');
        Route::post('/exam/destroy', 'ExamController@destroy');
        Route::post('/exam/undestroy', 'ExamController@undestroy');
        Route::post('/exam/list/destroyeds', 'ExamController@listdestroyeds');

        //Exam attachments
        Route::post('/exam/attachment/upload', 'ExamAttachmentController@store');
        Route::post('/exam/attachment/destroy', 'ExamAttachmentController@destroy');

        //evaluation resources OK
        Route::post('/evaluation/list', 'EvaluationController@index');
        Route::post('/evaluation/store', 'EvaluationController@store');
        Route::post('/evaluation/update', 'EvaluationController@update');
        Route::post('/evaluation/history/index', 'EvaluationController@indexHistory');
        Route::post('/evaluation/destroy', 'EvaluationController@destroy');
        Route::post('/evaluation/undestroy', 'EvaluationController@undestroy');
        Route::post('/evaluation/list/destroyeds', 'EvaluationController@listdestroyeds');

        Route::post('/evaluation/photo/upload', 'EvaluationPhotoController@store');
        Route::post('/evaluation/photo/destroy', 'EvaluationPhotoController@destroy');

        //Activity resources
        Route::get('/activity/client_list/{$}', 'ActivityController@client_list');
        Route::post('/activity/cre', 'ActivityController@update');
        Route::get('/activity/destroy/{id}', 'ActivityController@destroy');


    });

    //Client protected routes 
    Route::group(['middleware' => 'auth:client'], function () {

        //Activties
        Route::get('/activity/list/{id}', 'ActivityController@client_list');
        Route::post('/activity/store', 'ActivityController@client_store');

        //Client Profile update
        Route::post('/profile/show', 'ClientController@show');
        Route::post('/profile/update', 'ClientController@update');
        Route::get('/profile/show/{id}', 'ClientController@show');



        //activity resources
        Route::post('/activity/create', 'ActivityController@store');
        Route::post('/activity/update', 'ActivityController@update');
        Route::get('/activity/destroy/{id}', 'ActivityController@destroy');

        //calendar
        Route::post('/calendar/list', 'ScheduleController@clientCalendar');


        //Company resources
        Route::group(['prefix' => 'company'], function() {

            Route::get('/list', 'CompanyController@clientCompanies');
            Route::get('/full_list', 'CompanyController@companiesFullList');

            Route::post('/solicitation', 'ClientController@companySolicitation');
            Route::post('/accept_solicitation', 'ClientController@acceptCompanySolicitation');
            Route::post('/remove_solicitation', 'ClientController@removeCompanySolicitation');

            Route::post('/update_relationship', 'ClientController@updateCompanyRelationship');

            //rating
            Route::post('/rating/create', 'CompanyRatingController@store');
            Route::post('/rating/update', 'CompanyRatingController@update');
            Route::get('/rating/destroy/{id}', 'CompanyRatingController@destroy');

            //calendar settings to reschedule
            Route::post('/calendar_settings/to_reschedule', 'ProfessionalCalendarSettingController@toReschedule');

            //reschedule
            Route::post('/schedule/reschedule', 'ScheduleController@reschedule');
            Route::post('/schedule/cancel', 'ScheduleController@cancel');
        });

        //Professional rating
        Route::post('/professional/rating/create', 'ProfessionalRatingController@store');
        Route::post('/professional/rating/update', 'ProfessionalRatingController@update');
        Route::get('/professional/rating/destroy/{id}', 'ProfessionalRatingController@destroy');

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
 * Oracle
 */
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

