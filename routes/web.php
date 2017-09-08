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
use App\Models\Professional;

Route::post('/leadStoreForm', 'LandingController@leadStoreForm');

//New Landing


Route::group(['as' => 'landing.'], function () {

	//Index
	Route::get('/', ['uses' => 'LandingController@index', 'as' => 'index']);
	Route::post('/sendContact', ['uses' => 'LandingController@sendContactForm', 'as' => 'send-contact-form']);

    // Inviteds
    Route::get('/convite', ['uses' => 'LandingController@invitedChoice', 'as' => 'create']);
    Route::get('/convite/cliente', ['uses' => 'LandingController@invitedClient', 'as' => 'create.client']);
    Route::post('/convite/cliente/send-signup', ['uses' => 'LandingController@signupClient', 'as' => 'client.send.sigup']);
    Route::get('/convite/profissional', ['uses' => 'LandingController@invitedProfessional', 'as' => 'create.profissional']);
    Route::post('/convite/profissional/send-signup', ['uses' => 'LandingController@signupProfessional', 'as' => 'professional.send.sigup']);

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
        Route::get('/sobre', ['uses' => 'LandingController@forClientLanding', 'as' => 'about']);
    	Route::get('/{id}', ['uses' => 'LandingController@showClient', 'as' => 'show']);
	});

	//Professionals
	Route::group(['prefix' => 'profissionais', 'as' => 'professionals.'], function () {
    	Route::get('/sobre', ['uses' => 'LandingController@forProfessionalsLanding', 'as' => 'about']);
    	Route::get('/cadastro', ['uses' => 'LandingController@registerProfessional', 'as' => 'signup']);
    	Route::post('/sendSignupForm', ['uses' => 'LandingController@sendSignupForm', 'as' => 'send-signup-form']);
    	Route::get('/cadastro/sucesso', ['uses' => 'LandingController@signupSuccess', 'as' => 'signup-success']);
		Route::get('/login', ['uses' => 'LandingController@showProfessionalLogin', 'as' => 'login']);
		Route::post('/login', ['uses' => 'Auth\ProfessionalLoginController@landingLogin', 'as' => 'post-login']);
        Route::get('/{id}', ['uses' => 'LandingController@showProfessional', 'as' => 'show']);
	});

    Route::get('/termos-de-uso', ['uses' => 'LandingController@terms', 'as' => 'terms']);
    Route::get('/politicas', ['uses' => 'LandingController@privacy', 'as' => 'privacy']);

});


//Professionals
Route::group(['prefix' => 'profissional', 'as' => 'professional.'], function () {

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth:professional_web', 'check.owner']], function () {
        Route::get('/empresas', ['uses' => 'DashboardController@companiesIndex', 'as' => 'companies.list']);
        Route::get('/empresas/{id}', ['uses' => 'DashboardController@companyShow', 'as' => 'company.show']);
        Route::get('/empresas/editar/{id}', ['uses' => 'DashboardController@showCompanyEdit', 'as' => 'company.edit']);
        Route::post('/empresas/update', ['uses' => 'DashboardController@companyUpate', 'as' => 'company.update']);
    });

    Route::post('/logout', ['uses' => 'Auth\ProfessionalLoginController@logout', 'as' => 'logout']);
});





Route::get('/settings/test-email/{template}', function ($template) {
    return view($template);
});

Route::get('sitemap', function(){

    // create new sitemap object
    $sitemap = App::make("sitemap");

    $root = \Request::root();

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    // by default cache is disabled
    //$sitemap->setCache('laravel.sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached())
    {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add($root . '/clientes/sobre', \Carbon\Carbon::now(), '1.0', 'monthly');
        $sitemap->add($root . '/profissionais/sobre', \Carbon\Carbon::now(), '1.0', 'monthly');
        $sitemap->add($root . '/buscar', \Carbon\Carbon::now(), '1.0', 'monthly');

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

            $sitemap->add($root . '/empresas/'. $company->slug, $company->updated_at, '1.0', 'daily', $photos);
        }

        $cities = Company::select('city', 'lat', 'lng')->groupBy('city', 'lat', 'lng')->get();

        foreach($cities as $city){
            $sitemap->add($root . '/buscar?city=' . $city->city . '&lat=' . $city->lat . '&lng=' . $city->lng, \Carbon\Carbon::now(), '1.0', 'daily');
        }

        $categories = \App\Models\Category::get();

        foreach($categories as $category){
            $sitemap->add($root . '/buscar?category=' . $category->slug, \Carbon\Carbon::now(), '1.0', 'daily');
        }

        $professionals = Professional::all();

        foreach($professionals as $professional){

            $photos = [];
            foreach ($professional->photos as $photo) {
                $photos[] = [
                    'url' => $photo->photo_url,
                    'title' => 'Imagem de '. $professional->full_name ,
                    'caption' => 'Imagem de '. $professional->full_name
                ];
            }

            $sitemap->add($root . '/profissionais/'. $professional->id, $professional->updated_at, '1.0', 'daily', $photos);
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
