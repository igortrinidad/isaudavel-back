<?php

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
                Route::post('/schedules/list', 'ScheduleController@clientSchedules');


                //Company client Observations
                Route::group(['prefix' => 'observation'], function(){
                    Route::post('/list', 'CompanyClientObservationController@index');
                    Route::post('/create', 'CompanyClientObservationController@store');
                    Route::get('/destroy/{id}', 'CompanyClientObservationController@destroy');
                });


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
            Route::post('/category/calendar_settings/config', 'CategoryCalendarSettingController@index');
            Route::post('/category/calendar_settings/update', 'CategoryCalendarSettingController@update');
            Route::post('/category/calendar_settings/to_reschedule', 'CategoryCalendarSettingController@toReschedule');


            //Professional calendar settings
            Route::post('/professional/calendar_settings/list', 'ProfessionalCalendarSettingController@index');
            Route::post('/professional/calendar_settings/to_reschedule', 'ProfessionalCalendarSettingController@toReschedule');
            Route::post('/professional/calendar_settings', 'ProfessionalCalendarSettingController@show');
            Route::post('/professional/calendar_settings/update', 'ProfessionalCalendarSettingController@update');


            //Invoice
            Route::group(['prefix' => 'invoice'], function(){
                Route::post('/list', 'InvoiceController@index');
                Route::post('/client_list', 'InvoiceController@clientList');
                Route::post('/store', 'InvoiceController@store');
                Route::post('/update', 'InvoiceController@update');
                Route::post('/destroy', 'InvoiceController@destroy');
                Route::get('/schedules/{id}', 'ScheduleController@byInvoice');
            });

            //Schedule
            Route::group(['prefix' => 'schedule'], function(){
                Route::post('/calendar_list', 'ScheduleController@forCalendar');
                Route::post('/calendar_list_new', 'ScheduleController@forCalendarNew'); // for compatibility
                Route::post('/by_day', 'ScheduleController@schedulesByDay');
                Route::post('/reschedule', 'ScheduleController@reschedule');
                Route::post('/confirm', 'ScheduleController@confirm');
                Route::post('/cancel', 'ScheduleController@cancel');
                Route::post('/update', 'ScheduleController@update');
                Route::post('/destroy_all', 'ScheduleController@destroyAll');
                Route::get('/show/{id}', 'ScheduleController@show');
                Route::post('/schedules_by_professional_and_date', 'ScheduleController@schedules_by_professional_and_date');
            });

            //Single schedule
            Route::group(['prefix' => 'single_schedule'], function(){
                Route::post('/list', 'SingleScheduleController@index');
                Route::post('/store', 'SingleScheduleController@store');
                Route::post('/update', 'SingleScheduleController@update');
                Route::post('/reschedule', 'SingleScheduleController@reschedule');
                Route::post('/confirm', 'SingleScheduleController@confirm');
                Route::post('/cancel', 'SingleScheduleController@cancel');
                Route::get('/show/{id}', 'SingleScheduleController@show');
                Route::get('/destroy/{id}', 'SingleScheduleController@destroy');

            });

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

        //Professional Recomendations
        Route::get('/recomendation/received/{id}', 'RecomendationController@receivedList');
        Route::get('/recomendation/sent/{id}', 'RecomendationController@sentList');
        Route::post('/recomendation/create', 'RecomendationController@store');
        Route::post('/recomendation/update', 'RecomendationController@update');
        Route::get('/recomendation/destroy/{id}', 'RecomendationController@destroy');

        //Professional Calendar
        Route::post('/calendar/list', 'ScheduleController@professionalCalendar');
        Route::post('/calendar/list_new', 'ScheduleController@professionalCalendarNew'); //for compatibility
        Route::post('/calendar/reschedule', 'ScheduleController@reschedule');
        Route::post('/calendar/settings', 'ProfessionalCalendarSettingController@list_professional_dashboard');
        Route::get('/calendar/settings/show/{id}', 'ProfessionalCalendarSettingController@show_professional_dashboard');
        Route::post('/calendar/settings/update', 'ProfessionalCalendarSettingController@update');
        Route::post('/calendar/workdays/company/list', 'ProfessionalCalendarSettingController@company_professional_workdays');


        //Client
        Route::group(['prefix' => 'client'], function(){
            //list
            Route::post('/list', 'ClientController@professionalClients');
            Route::post('/search', 'ClientController@searchProfessional');

            //Client professional relationship
            Route::post('/solicitation', 'ClientController@professionalSolicitation');
            Route::post('/accept_solicitation', 'ClientController@acceptProfessionalSolicitation');
            Route::post('/remove_solicitation', 'ClientController@removeProfessionalSolicitation');
            Route::post('/update_relationship', 'ClientController@updateProfessionalRelationship');
            Route::post('/reactivate_solicitation', 'ClientController@reactivateProfessionalRelationship');
        });

        //profile update
        Route::get('/profile/show/{id}', 'ProfessionalController@show');
        Route::post('/profile/update', 'ProfessionalController@update');
    });

    //Open routes
    Route::post('/list', 'ProfessionalController@listPublic');
    Route::post('/category/search', 'ProfessionalController@searchByCategory');
    Route::get('/show/{id}', 'ProfessionalController@show');
    Route::get('/public/show/{slug}', 'ProfessionalController@showPublic');
});