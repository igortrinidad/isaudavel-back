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


Route::post('/leadStoreForm', 'LandingController@leadStoreForm');


Route::get('/testschedule', 'ScheduleController@insights_by_date');

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
		Route::get('/', ['uses' => 'LandingController@company_search', 'as' => 'index']);
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

    //Receitas
    Route::group(['prefix' => 'receitas', 'as' => 'recipes.'], function () {
        Route::get('/', ['uses' => 'LandingController@ListRecipes', 'as' => 'list']);
        Route::get('/{slug}', ['uses' => 'LandingController@ShowRecipe', 'as' => 'show']);
        Route::get('/imprimir/{slug}', ['uses' => 'MealRecipeController@generate_pdf', 'as' => 'pdf']);
    });

    //Artigos
	Route::group(['prefix' => 'artigos', 'as' => 'articles.'], function () {
		Route::get('/', ['uses' => 'SiteArticleController@list_for_site', 'as' => 'list']);
        Route::get('/{slug}', ['uses' => 'SiteArticleController@show_for_site', 'as' => 'show']);
	});

    //Tools na landing
    Route::group(['prefix' => 'tools', 'as' => 'tools.'], function () {

        //PRINT PDF
        Route::group(['prefix' => 'print', 'as' => 'print.'], function () {
            Route::get('/trainning/{id}', ['uses' => 'TrainningController@generate_pdf', 'as' => 'pdf']);
            Route::get('/diet/{id}', ['uses' => 'DietController@generate_pdf', 'as' => 'pdf']);
            Route::get('/evaluation/{id}', ['uses' => 'EvaluationController@generate_pdf', 'as' => 'pdf']);
            Route::get('/recipes/{slug}', ['uses' => 'MealRecipeController@generate_pdf', 'as' => 'pdf']);
        });

    });

	Route::group(['prefix' => 'cadastro', 'as' => 'signup'], function() {
        Route::get('/', ['uses' => 'LandingController@formSignupProfessionalUser', 'as' => 'signup']);

        Route::get('/confirmar-email', ['uses' => 'LandingController@signupProfessionalEmailConfirmationWarning', 'as' => '.email-confirmation']);
        Route::get('/confirmar-email/{id}', ['uses' => 'LandingController@signupProfessionalEmailConfirmationSuccess', 'as' => '.email-confirmation-success']);
        Route::get('/empresa', ['uses' => 'LandingController@registerCompany', 'as' => '.company']);
        Route::post('/store-company', ['uses' => 'LandingController@signupCompanyStore', 'as' => '.company.store']);
		Route::get('/finalizar', ['uses' => 'LandingController@registerSelectType', 'as' => '.plan.chooser']);
        Route::get('/plano-atualizado', ['uses' => 'LandingController@updateProfessionalPlan', 'as' => '.update-plan']);
	});

	//Professionals
	Route::group(['prefix' => 'profissionais', 'as' => 'professionals.'], function () {

        Route::get('/', ['uses' => 'LandingController@professional_search', 'as' => 'search']);
    	Route::get('/sobre', ['uses' => 'LandingController@forProfessionalsLanding', 'as' => 'about']);
    	Route::get('/cadastro', ['uses' => 'LandingController@registerProfessional', 'as' => 'signup']);
    	Route::post('/storeSignupProfessionalUser', ['uses' => 'LandingController@storeSignupProfessionalUser', 'as' => 'send-signup-form']);
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

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth:professional_web']], function () {
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

        Route::get('/follow-up', ['uses' => 'OracleController@followUp', 'as' => 'follow-up']);

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

            Route::get('{id}/profissionais/lista', ['uses' => 'OracleController@companyProfessionalList', 'as' => 'professional.list']);
            Route::post('/profissionais/excluir_vinculo', ['uses' => 'OracleController@removeProfessionalFromCompany', 'as' => 'professional.remove_from_company']);
            Route::post('/profissionais/adicionar_vinculo', ['uses' => 'OracleController@addProfessionalToCompany', 'as' => 'professional.add_to_company']);

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
            Route::post('remover', ['uses' => 'OracleController@destroyEvent', 'as' => 'destroy']);
        });

        //Receitas
        Route::group(['prefix' => 'receitas', 'as' => 'recipes.'], function () {
            Route::get('/', ['uses' => 'OracleController@recipesList', 'as' => 'list']);
            Route::get('editar/{id}', ['uses' => 'OracleController@editRecipe', 'as' => 'edit']);
            Route::post('update', ['uses' => 'OracleController@recipeUpdate', 'as' => 'update']);
            Route::post('remover', ['uses' => 'OracleController@destroyRecipe', 'as' => 'destroy']);
        });

        //Artigos
        Route::group(['prefix' => 'artigos', 'as' => 'articles.'], function () {
            Route::get('/', ['uses' => 'SiteArticleController@index', 'as' => 'list']);
            Route::get('/criar', ['uses' => 'SiteArticleController@create', 'as' => 'create']);
            Route::post('/store', ['uses' => 'SiteArticleController@store', 'as' => 'store']);
            Route::post('/update', ['uses' => 'SiteArticleController@update', 'as' => 'update']);
            Route::get('/editar/{id}', ['uses' => 'SiteArticleController@edit', 'as' => 'edit']);
            Route::get('/destroy/{id}', ['uses' => 'SiteArticleController@destroy', 'as' => 'destroy']);
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
            Route::get('/criar', ['uses' => 'OracleController@newOracle', 'as' => 'create']);
            Route::post('store', ['uses' => 'OracleController@StoreNewOracle', 'as' => 'store']);
            Route::get('exibir/{id}', ['uses' => 'OracleController@oracleShow', 'as' => 'show']);
            Route::post('update', ['uses' => 'OracleController@oracleUpdate', 'as' => 'update']);
        });

        // Modalidades
        Route::group(['prefix' => 'modalidades', 'as' => 'modalities.'], function () {
            Route::get('/', ['uses' => 'OracleController@modalitiesList', 'as' => 'list']);
            Route::get('criar', ['uses' => 'OracleController@createModality', 'as' => 'create']);
            Route::post('store', ['uses' => 'OracleController@storeModality', 'as' => 'store']);
            Route::get('editar/{id}', ['uses' => 'OracleController@editModality', 'as' => 'edit']);
            Route::post('update', ['uses' => 'OracleController@modalityUpdate', 'as' => 'update']);
            Route::post('remover', ['uses' => 'OracleController@destroyModality', 'as' => 'destroy']);
        });

        Route::group(['prefix' => 'submodalidades', 'as' => 'submodalities.'], function () {
            Route::post('criar', ['uses' => 'SubModalityController@store', 'as' => 'store']);
            Route::post('atualizar', ['uses' => 'SubModalityController@update', 'as' => 'update']);
            Route::post('remover', ['uses' => 'SubModalityController@destroy', 'as' => 'destroy']);
        });

        // Sales (Hubspot)
        Route::group(['prefix' => 'vendas', 'as' => 'sales.'], function () {
            Route::get('/', ['uses' => 'SalesController@index', 'as' => 'dashboard']);
        });





        Route::get('/meu-perfil', ['uses' => 'OracleController@profileShow', 'as' => 'profile.show']);
        Route::get('/notificacoes', ['uses' => 'OracleController@notifications', 'as' => 'notifications.show']);

        Route::post('/logout', ['uses' => 'Auth\OracleLoginController@logout', 'as' => 'logout']);
    });
});

//Teste email template
Route::get('/settings/test-email/{template}', function ($template) {
    return view($template);
});

//Generate sitemap
Route::get('/sitemap', ['uses' => 'SystemController@generate_sitemap', 'as' => 'sitemap']);
