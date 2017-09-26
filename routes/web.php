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

    //Events
    Route::group(['prefix' => 'eventos', 'as' => 'events.'], function () {
        Route::get('/', ['uses' => 'LandingController@ListEvents', 'as' => 'list']);
        Route::get('/{slug}', ['uses' => 'LandingController@ShowEvent', 'as' => 'show']);
    });

    //Events
	Route::group(['prefix' => 'receitas', 'as' => 'recipes.'], function () {
		Route::get('/', ['uses' => 'LandingController@ListRecipes', 'as' => 'list']);
        Route::get('/{slug}', ['uses' => 'LandingController@ShowRecipe', 'as' => 'show']);
	});

	//Professionals
	Route::group(['prefix' => 'profissionais', 'as' => 'professionals.'], function () {
    	Route::get('/sobre', ['uses' => 'LandingController@forProfessionalsLanding', 'as' => 'about']);
    	Route::get('/cadastro', ['uses' => 'LandingController@registerProfessional', 'as' => 'signup']);
    	Route::post('/sendSignupForm', ['uses' => 'LandingController@sendSignupForm', 'as' => 'send-signup-form']);
    	Route::get('/cadastro/sucesso', ['uses' => 'LandingController@signupSuccess', 'as' => 'signup-success']);
		Route::get('/login', ['uses' => 'LandingController@showProfessionalLogin', 'as' => 'login']);
		Route::post('/login', ['uses' => 'Auth\ProfessionalLoginController@landingLogin', 'as' => 'post-login']);
        Route::get('/{slug}', ['uses' => 'LandingController@showProfessional', 'as' => 'show']);
	});

    Route::get('/termos-de-uso', ['uses' => 'LandingController@terms', 'as' => 'terms']);
    Route::get('/politicas', ['uses' => 'LandingController@privacy', 'as' => 'privacy']);

});


//Professionals
Route::group(['prefix' => 'profissional', 'as' => 'professional.'], function () {

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth:professional_web', 'check.owner']], function () {
        Route::get('/empresas', ['uses' => 'DashboardController@companiesIndex', 'as' => 'companies.list']);
        Route::get('/empresas/{id}', ['uses' => 'DashboardController@companyShow', 'as' => 'company.show']);
        Route::get('/empresas/{id}/faturas', ['uses' => 'DashboardController@invoicesList', 'as' => 'invoices.list']);
        Route::get('/empresas/{id}/faturas/{invoice_id}', ['uses' => 'DashboardController@invoiceShow', 'as' => 'invoice.show']);
        Route::get('/empresas/editar/{id}', ['uses' => 'DashboardController@showCompanyEdit', 'as' => 'company.edit']);
        Route::post('/empresas/update', ['uses' => 'DashboardController@companyUpate', 'as' => 'company.update']);
        Route::post('/empresas/subscription/update', ['uses' => 'DashboardController@subscriptionUpdate', 'as' => 'subscription.update']);
    });

    Route::post('/logout', ['uses' => 'Auth\ProfessionalLoginController@logout', 'as' => 'logout']);
});

//Oracle routes
Route::group(['prefix' => 'oracle', 'as' => 'oracle.'], function () {

    Route::get('/login', ['uses' => 'OracleController@showLogin', 'as' => 'login']);
    Route::post('/login', ['uses' => 'Auth\OracleLoginController@landingLogin', 'as' => 'post.login']);
    
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth:oracle_web']], function () {

        Route::get('/', ['uses' => 'OracleController@index', 'as' => 'home']);

        //companies
        Route::group(['prefix' => 'empresas', 'as' => 'companies.'], function () {

            Route::get('/', ['uses' => 'OracleController@companiesList', 'as' => 'list']);
            Route::get('/editar/{id}', ['uses' => 'OracleController@companyEdit', 'as' => 'edit']);
            Route::post('/update', ['uses' => 'OracleController@companyUpdate', 'as' => 'update']);
            Route::get('/assinatura/{id}', ['uses' => 'OracleController@companySubscription', 'as' => 'subscription']);
            Route::get('/nova-assinatura/{id}', ['uses' => 'OracleController@subscriptionCreate', 'as' => 'subscription.create']);
            Route::post('/assinatura/create', ['uses' => 'OracleController@subscriptionStore', 'as' => 'subscription.store']);
            Route::post('/assinatura/update', ['uses' => 'OracleController@subscriptionUpdate', 'as' => 'subscription.update']);
            Route::get('/{id}/faturas', ['uses' => 'OracleController@companyInvoices', 'as' => 'invoices']);
            Route::get('/{id}/faturas/{invoice_id}', ['uses' => 'OracleController@invoiceShow', 'as' => 'invoice.show']);
            Route::post('/faturas/atualizar', ['uses' => 'OracleController@invoiceUpdate', 'as' => 'invoice.update']);

        });

        //clients
        Route::group(['prefix' => 'clientes', 'as' => 'clients.'], function () {
            Route::get('/', ['uses' => 'OracleController@clientsList', 'as' => 'list']);
            Route::get('exibir/{id}', ['uses' => 'OracleController@clientShow', 'as' => 'show']);
            Route::post('update', ['uses' => 'OracleController@clientUpdate', 'as' => 'update']);
        });

        //Professionals
        Route::group(['prefix' => 'profissionais', 'as' => 'professionals.'], function () {
            Route::get('/', ['uses' => 'OracleController@professionalsList', 'as' => 'list']);
            Route::get('exibir/{id}', ['uses' => 'OracleController@professionalShow', 'as' => 'show']);
            Route::post('update', ['uses' => 'OracleController@professionalUpdate', 'as' => 'update']);
        });

        //Eventos
        Route::group(['prefix' => 'eventos', 'as' => 'events.'], function () {
            Route::get('/', ['uses' => 'OracleController@eventsList', 'as' => 'list']);
            Route::get('editar/{id}', ['uses' => 'OracleController@editEvent', 'as' => 'edit']);
            Route::post('update', ['uses' => 'OracleController@eventUpdate', 'as' => 'update']);
        });

        //Receitas
        Route::group(['prefix' => 'receitas', 'as' => 'recipes.'], function () {
            Route::get('/', ['uses' => 'OracleController@recipesList', 'as' => 'list']);
            Route::get('editar/{id}', ['uses' => 'OracleController@editRecipe', 'as' => 'edit']);
            Route::post('update', ['uses' => 'OracleController@recipeUpdate', 'as' => 'update']);
        });

        //Eval index
        Route::group(['prefix' => 'indices', 'as' => 'eval-index.'], function () {
            Route::get('/listar', ['uses' => 'OracleController@eval_index_list', 'as' => 'list']);
            Route::get('/editar/{id}', ['uses' => 'OracleController@eval_index_edit', 'as' => 'edit']);
            Route::post('/update', ['uses' => 'OracleController@update_eval_index', 'as' => 'update']);
        });

        //Versão do app
        Route::group(['prefix' => 'sistema', 'as' => 'system.'], function () {
            Route::get('/editar-versao', ['uses' => 'SystemController@show_edit_version', 'as' => 'edit-version']);
            Route::post('/update-version', ['uses' => 'SystemController@update_version', 'as' => 'update']);
        });

        //Oracle
        Route::group(['prefix' => 'administradores', 'as' => 'oracles.'], function () {
            Route::get('/', ['uses' => 'OracleController@oraclesList', 'as' => 'list']);
            Route::get('exibir/{id}', ['uses' => 'OracleController@oracleShow', 'as' => 'show']);
            Route::post('update', ['uses' => 'OracleController@oracleUpdate', 'as' => 'update']);
        });


        Route::get('/meu-perfil', ['uses' => 'OracleController@profileShow', 'as' => 'profile.show']);

        Route::post('/logout', ['uses' => 'Auth\OracleLoginController@logout', 'as' => 'logout']);
    });
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

            $sitemap->add($root . '/profissionais/'. $professional->slug, $professional->updated_at, '1.0', 'daily', $photos);
        }

        $events = \App\Models\Event::all();

        foreach($events as $event){

            $photos = [];
            foreach ($event->photos as $photo) {
                $photos[] = [
                    'url' => $photo->photo_url,
                    'title' => 'Imagem de '. $event->name ,
                    'caption' => 'Imagem de '. $event->name
                ];
            }

            $sitemap->add($root . '/eventos/'. $event->slug, $event->updated_at, '1.0', 'daily', $photos);
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
