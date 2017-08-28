<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LandingController@index');
Route::post('/leadStoreForm', 'LandingController@leadStoreForm');

//New Landing


Route::group(['prefix' => 'new-landing', 'as' => 'landing.'], function () {

	Route::get('/', ['uses' => 'LandingController@NewIndex', 'as' => 'index']);
    Route::get('/para-voce', ['uses' => 'LandingController@showClientLanding', 'as' => 'index']);
    Route::get('/para-profissionais', ['uses' => 'LandingController@showProfessionalsLanding', 'as' => 'index']);


	//Companies
	Route::group(['prefix' => 'buscar', 'as' => 'search.'], function () {
		Route::get('/', ['uses' => 'LandingController@NewIndexSearch', 'as' => 'index']);
	});

	Route::group(['prefix' => 'empresas', 'as' => 'companies.'], function () {
		Route::get('/cadastrar', ['uses' => 'LandingController@createCompany', 'as' => 'create']);
		Route::get('/{slug}', ['uses' => 'LandingController@showCompany', 'as' => 'show']);
	});

	Route::group(['prefix' => 'profissionais', 'as' => 'professionals.'], function () {
		Route::get('/{id}', ['uses' => 'LandingController@showProfessional', 'as' => 'show']);



		Route::group(['prefix' => 'profissionais', 'as' => 'auth.'], function () {
			Route::get('/login', ['uses' => 'LandingController@showProfessionalLogin', 'as' => 'login']);
		});
	});

});




Route::get('/settings/test-email/{template}', function ($template) {
    return view($template);
});
