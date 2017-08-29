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

	//Index
	Route::get('/', ['uses' => 'LandingController@NewIndex', 'as' => 'index']);

	//Companies
	Route::group(['prefix' => 'buscar', 'as' => 'search.'], function () {
		Route::get('/', ['uses' => 'LandingController@NewIndexSearch', 'as' => 'index']);
	});

	//Search
	Route::group(['prefix' => 'empresas', 'as' => 'companies.'], function () {
		Route::get('/cadastrar', ['uses' => 'LandingController@createCompany', 'as' => 'create']);
		Route::get('/{slug}', ['uses' => 'LandingController@showCompany', 'as' => 'show']);
	});

	//Clients
	Route::group(['prefix' => 'clientes', 'as' => 'clients.'], function () {
    	Route::get('/sobre', ['uses' => 'LandingController@showClientLanding', 'as' => 'about']);
	});

	//Professionals
	Route::group(['prefix' => 'profissionais', 'as' => 'professionals.'], function () {
    	Route::get('/sobre', ['uses' => 'LandingController@showProfessionalsLanding', 'as' => 'about']);
    	Route::get('/cadastro', ['uses' => 'LandingController@registerProfessional', 'as' => 'signup']);
		Route::get('/{id}', ['uses' => 'LandingController@showProfessional', 'as' => 'show']);
		Route::get('/login', ['uses' => 'LandingController@showProfessionalLogin', 'as' => 'login']);
	});

});




Route::get('/settings/test-email/{template}', function ($template) {
    return view($template);
});
