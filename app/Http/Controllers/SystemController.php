<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\AppVersion;


class SystemController extends Controller
{


    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function send_log_erro(Request $request)
    {
        
        //Email para nós
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = 'Erro no iSaudavel';
        $data['messageOne'] = $request->get('log');
        $data['messageSubject'] = 'Log de erro';

        $mail = \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@isaudavel.com', 'Erro iSaudavel');
            $message->to('contato@maisbartenders.com.br', 'Erro iSaudavel')->subject($data['messageSubject']);
            $message->cc('contato@matheuslima.com.br', 'Erro iSaudavel')->subject($data['messageSubject']);
        });

        return response()->json(['mail' => $mail]);


    }


    /**
     * GEt version of the application
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function get_last_production_version(Request $request)
    {

        $version = AppVersion::where('production', true)->orderBy('created_at', 'DESC')->first();

        return response()->json(['version' => $version->version]);

    }

    /**
     * Show edit version of the application
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show_edit_version(Request $request)
    {

        $actual_version = AppVersion::where('production', true)->orderBy('created_at', 'DESC')->first();

        $actual_version = $actual_version->version;

        return view('oracle.dashboard.system.version-edit', compact('actual_version'));

    }

    /**
     * Update version of the application
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_version(Request $request)
    {

        $actual_version = AppVersion::where('version', $request->get('actual_version'))->update(['production' => 0]);

        $new_version = AppVersion::create([
            'version' => $request->get('new_version'),
            'production' => 1,
            'test' => 0
        ]);

        $actual_version = $new_version->version;

        return view('oracle.dashboard.system.version-edit', compact('actual_version'));

    }

    /**
     * Update version of the application
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generate_sitemap(Request $request)
    {

        // create new sitemap object
        $sitemap = \App::make("sitemap");

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
            $sitemap->add($root . '/profissionais/sobre', \Carbon\Carbon::now(), '1.0', 'daily');
            $sitemap->add($root . '/eventos', \Carbon\Carbon::now(), '1.0', 'daily');
            $sitemap->add($root . '/receitas', \Carbon\Carbon::now(), '1.0', 'daily');
            $sitemap->add($root . '/artigos', \Carbon\Carbon::now(), '1.0', 'daily');
            $sitemap->add($root . '/buscar', \Carbon\Carbon::now(), '1.0', 'daily');
            $sitemap->add($root . '/profissionais', \Carbon\Carbon::now(), '1.0', 'daily');

            //Empresas
            $companies = \App\Models\Company::all();

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

            //Cidades
            $cities = \App\Models\Company::select('city', 'lat', 'lng')->groupBy('city', 'lat', 'lng')->get();

            foreach($cities as $city){
                $sitemap->add($root . '/buscar?city=' . str_replace(' ', '+',$city->city) . '&lat=' . $city->lat . '&lng=' . $city->lng, \Carbon\Carbon::now(), '1.0', 'daily');
            }

            $categories = \App\Models\Category::get();
            //Lista de URL de categorias de empresas
            foreach($categories as $category){
                $sitemap->add($root . '/buscar?category=' . $category->slug, \Carbon\Carbon::now(), '1.0', 'daily');
            }

            //Lista de URL de categorias de profissionais
            foreach($categories as $category){
                $sitemap->add($root . '/profissionais?category=' . $category->slug, \Carbon\Carbon::now(), '1.0', 'daily');
            }

            //Profissionais
            $professionals = \App\Models\Professional::all();

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

            //Eventos
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

            //Receitas
            $recipes = \App\Models\MealRecipe::all();

            foreach($recipes as $recipe){

                $photos = [];
                foreach ($recipe->photos as $photo) {
                    $photos[] = [
                        'url' => $photo->photo_url,
                        'title' => 'Imagem de '. $recipe->title,
                        'caption' => 'Imagem de '. $recipe->title
                    ];
                }

                $sitemap->add($root . '/receitas/'. $recipe->slug, $recipe->updated_at, '1.0', 'daily', $photos);
            }

            //Artigos
            $articles = \App\Models\SiteArticle::all();

            foreach($articles as $article){

                $photos = [];
                $photos[] = [
                    'url' => $article->avatar,
                    'title' => 'Imagem de '. $article->title,
                    'caption' => 'Imagem de '. $article->title
                ];

                $sitemap->add($root . '/artigos/'. $article->slug, $article->updated_at, '1.0', 'daily', $photos);
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

    }




}
