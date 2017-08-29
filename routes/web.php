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

use App\Models\Company;

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

Route::get('sitemap', function(){

    // create new sitemap object
    $sitemap = App::make("sitemap");

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    // by default cache is disabled
    //$sitemap->setCache('laravel.sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached())
    {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add('/new-landing/clientes/sobre', \Carbon\Carbon::now(), '1.0', 'monthly');
        $sitemap->add('/new-landing/profissionais/sobre', \Carbon\Carbon::now(), '1.0', 'monthly');

        $companies = Company::all();

        foreach($companies as $company){

            $photos = [];
            foreach ($company->photos as $photo) {
                $photos[] = [
                    'url' => $photo->photo_url,
                    'title' => 'Imagem de '. $company->name ,
                    'caption' => 'Imagem de '. $company->name
                ];
            }

            $sitemap->add('/new-landing/empresas/'. $company->slug, $company->updated_at, '1.0', 'daily', $photos);
        }

    }

    return $sitemap->render('xml');


    //Generate and store the xml file
    /*$sitemap->store('xml', 'sitemap');

    //Send the new sitemap to Google
    $url = 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.\Config('app.url').'/sitemap.xml';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);*/
});
