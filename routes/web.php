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


Route::group(['prefix' => 'new-landing'], function () {

	Route::get('/', 'LandingController@NewIndex');

	//Companies
	Route::group(['prefix' => 'buscar'], function () {
		Route::get('/buscar', 'LandingController@NewIndexSearch');
	});

	Route::group(['prefix' => 'empresas'], function () {
		Route::get('/{slug}', 'LandingController@showCompany');
	});

});




Route::get('/settings/test-email/{template}', function ($template) {
    return view($template);
});