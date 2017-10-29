<?php

use Illuminate\Http\Request;

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

        Route::post('/fcm_token', 'ClientController@fcmToken');

        //XP info
        Route::get('/current_xp', 'ClientController@XpInfo');
        Route::get('/infos', 'ClientController@infos');

        //Notifications
        Route::group(['prefix' => 'notification'], function() {

            Route::get('/list', 'ClientNotificationController@index');
            Route::get('/mark_readed/{id}', 'ClientNotificationController@markReaded');
            Route::get('/mark_all_readed', 'ClientNotificationController@markAllReaded');
        });

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
            Route::get('/show/{id}', 'CompanyController@showClient');

            Route::post('/show/schedules', 'ScheduleController@clientSchedules');
            Route::post('/show/invoices', 'InvoiceController@clientList');
            Route::post('/show/subscriptions', 'ScheduleController@clientSchedules');

            Route::post('/solicitation', 'ClientController@companySolicitation');
            Route::post('/accept_solicitation', 'ClientController@acceptCompanySolicitation');
            Route::post('/remove_solicitation', 'ClientController@removeCompanySolicitation');

            Route::post('/update_relationship', 'ClientController@updateCompanyRelationship');

            //rating
            Route::post('/rating/create', 'CompanyRatingController@store');
            Route::post('/rating/update', 'CompanyRatingController@update');
            Route::get('/rating/destroy/{id}', 'CompanyRatingController@destroy');
            Route::post('/rating/check', 'CompanyRatingController@checkRating');

            //professional calendar settings to reschedule
            Route::post('/calendar_settings/to_reschedule', 'ProfessionalCalendarSettingController@toReschedule');

            //category calendar settings to reschedule
            Route::post('/category/calendar_settings/to_reschedule', 'CategoryCalendarSettingController@toReschedule');

            //Schedule
            Route::post('/schedule/reschedule', 'ScheduleController@reschedule');
            Route::post('/schedule/cancel', 'ScheduleController@cancel');
            Route::get('/schedule/show/{id}', 'ScheduleController@show');

            //Single schedule
            Route::group(['prefix' => 'single_schedule'], function(){
                Route::post('/reschedule', 'SingleScheduleController@reschedule');
                Route::post('/cancel', 'SingleScheduleController@cancel');
                Route::get('/show/{id}', 'SingleScheduleController@show');
            });

        });

        //Professional  Resources
        Route::group(['prefix' => 'professional'], function() {

            Route::get('/list', 'ProfessionalController@clientProfessionals');

            //Professional rating
            Route::post('/rating/create', 'ProfessionalRatingController@store');
            Route::post('/rating/update', 'ProfessionalRatingController@update');
            Route::get('/rating/destroy/{id}', 'ProfessionalRatingController@destroy');
            Route::post('/rating/check', 'ProfessionalRatingController@checkRating');

            //Client professional relationship
            Route::post('/solicitation', 'ClientController@professionalSolicitation');
            Route::post('/accept_solicitation', 'ClientController@acceptProfessionalSolicitation');
            Route::post('/remove_solicitation', 'ClientController@removeProfessionalSolicitation');
            Route::post('/update_relationship', 'ClientController@updateProfessionalRelationship');
            Route::post('/reactivate_solicitation', 'ClientController@reactivateProfessionalRelationship');

        });
    });
});