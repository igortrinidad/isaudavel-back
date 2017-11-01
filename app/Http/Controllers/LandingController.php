<?php

namespace App\Http\Controllers;

use App\Events\OracleNotification;
use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use App\Models\MealRecipeTag;
use App\Models\MealType;
use App\Models\Modality;
use App\Models\SiteArticle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Lead;

use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Models\Client;
use App\Models\Professional;
use App\Models\MealRecipe;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

class LandingController extends Controller
{

    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::with('categories')->where('is_active', 1)->limit(8)->get();
        $events = Event::with('modality')->limit(8)->paginate(8);
        $recipes = MealRecipe::with('from')->orderBy('created_at', 'DESC')->limit(8)->get();
        $articles = SiteArticle::orderBy('created_at', 'DESC')->limit(8)->get();
        $categories = Category::all();

        return view('landing.home.about', compact('companies', 'events', 'categories', 'recipes', 'articles'));
    }

    /**
     * Company search
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function company_search(Request $request)
    {

        $categories = Category::all();

        if($request->query('category')){
            $category_fetched = Category::where('slug', $request->query('category'))->first();
        } else {

            if($request->query('city')){
                return redirect()->route('landing.search.index', [ 'category'=> $categories[0]->slug, 'city' => $request->query('city')]);
            } else {
                return redirect()->route('landing.search.index', [ 'category'=> $categories[0]->slug ]);
            }
        }

        if($request->query('city')){
            $city_fetched = $request->query('city');
        }

        //Se não tiver localização em lat e lng
        if( empty($request->query('lat')) && empty($request->query('lng')) ) {

            $companies = Company::with('categories')
                ->where('is_active', 1)
                ->whereHas('categories', function($query) use($request){
                    $query->where('slug', 'LIKE', '%'.$request->query('category') . '%');
            })->paginate(30);

        }

        //Se tiver localização em lat e lng
        if( !empty($request->query('lat')) && !empty($request->query('lng')) ){

            $user_lat = $request->query('lat');
            $user_lng = $request->query('lng');

            $companies = Company::select(\DB::raw("*,
                    (ATAN(SQRT(POW(COS(RADIANS(companies.lat)) * SIN(RADIANS(companies.lng)
                     - RADIANS('$user_lng')), 2) +POW(COS(RADIANS('$user_lat')) *
                     SIN(RADIANS(companies.lat)) - SIN(RADIANS('$user_lat')) * cos(RADIANS(companies.lat)) *
                     cos(RADIANS(companies.lng) - RADIANS('$user_lng')), 2)),SIN(RADIANS('$user_lat')) *
                     SIN(RADIANS(companies.lat)) + COS(RADIANS('$user_lat')) * COS(RADIANS(companies.lat)) *
                     COS(RADIANS(companies.lng) - RADIANS('$user_lng'))) * 6371000) as distance_m"))
                ->with(['professionals' => function($query){
                    $query->select('id', 'name', 'last_name')
                        ->with(['categories' => function($query){
                            $query->select('name');
                        }])->orderBy('name', 'asc');
                }])->where('is_active', 1)
                ->where('name', 'LIKE', '%' . $request->get('search') . '%' )
                    ->whereHas('categories', function($query) use($request){

                        if($request->get('category') === 'all'){
                            $query->where('slug', '<>', 'all');
                        }

                        if($request->get('category') != 'all'){
                            $query->where('slug', $request->get('category'));
                        }

                })->with(['categories' => function($query){
                    $query->select('name');
                }])->orderBy('distance_m', 'asc')
                ->paginate(12);

            foreach($companies as $company){
                $company->distance_km = round(($company->distance_m / 1000) , 2);
            }

        }

        return view('landing.companies.list', compact('companies', 'categories', 'category_fetched', 'city_fetched'));
    }

    /**
     * Professional search
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function professional_search(Request $request)
    {

        $categories = Category::all();

        if($request->query('category')){
            $category_fetched = Category::where('slug', $request->query('category'))->first();
        } else {

            if($request->query('city')){
                return redirect()->route('landing.professionals.search', [ 'category'=> $categories[0]->slug, 'city' => $request->query('city')]);
            } else {
                return redirect()->route('landing.professionals.search', [ 'category'=> $categories[0]->slug ]);
            }
        }

        $professionals = Professional::where('name', 'LIKE', '%' . $request->get('search') . '%' )
            ->whereHas('categories', function($query) use($request){
                $query->where('slug', $request->query('category'));
            })
            ->with(['categories' => function($query){
                $query->select('name');
            }])
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('landing.professionals.list', compact('professionals', 'categories', 'category_fetched'));

    }

    /**
     * Lista dos eventos
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ListEvents(Request $request)
    {
        $filters = is_array($request->get('filters')) ? $request->get('filters') : json_decode($request->get('filters'), true);

        $latitude = isset($filters['latitude']) ? $filters['latitude'] :  null;
        $longitude = isset($filters['longitude']) ? $filters['longitude'] : null;

        $events = Event::select(\DB::raw("*,
                (ATAN(SQRT(POW(COS(RADIANS(events.lat)) * SIN(RADIANS(events.lng)
                 - RADIANS('$longitude')), 2) +POW(COS(RADIANS('$latitude')) *
                 SIN(RADIANS(events.lat)) - SIN(RADIANS('$latitude')) * cos(RADIANS(events.lat)) *
                 cos(RADIANS(events.lng) - RADIANS('$longitude')), 2)),SIN(RADIANS('$latitude')) *
                 SIN(RADIANS(events.lat)) + COS(RADIANS('$latitude')) * COS(RADIANS(events.lat)) *
                 COS(RADIANS(events.lng) - RADIANS('$longitude'))) * 6371000) as distance_m"))
            ->whereHas('modality', function ($query) use ($filters) {
                if(!empty($filters['modalities'])) {
                    $query->whereIn('slug', $filters['modalities']);
                }
            })->whereHas('submodalities', function ($query) use ($filters) {
                if(!empty($filters['submodalities'])) {
                    $query->whereIn('slug',$filters['submodalities']);
                }
            })
            ->where(function($query) use($filters){

                if(!empty($filters['search'])){
                    $search = explode(' ', $filters['search']);
                    $query->where('name', 'LIKE', '%' . $filters['search']. '%');
                    $query->orWhereIn('name', $search);
                }
            })
            ->orderBy('distance_m', 'asc')
            ->paginate(10);

        $events->appends(['filters' => $filters]);

        $modalities = Modality::select('id', 'name', 'slug')->with(['submodalities' => function($query){
            $query->select('id','modality_id', 'name', 'slug')->orderBy('name');
        }])->orderBy('name')->get();

        $companies = Company::with('categories')->limit(8)->get();

        \JavaScript::put(['modalities' => $modalities, 'filters' => $filters]);

        return view('landing.events.list', compact('events', 'companies'));
    }

    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function LeadStoreForm(Request $request)
    {

        $lead = Lead::create($request->all());


        //Email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = 'LEAD iSaudavel';
        $data['messageOne'] = 'Nome: ' . $request['name'];
        $data['messageTwo'] = 'Email: ' . $request['email'];
        $data['messageThree'] = 'Phone: ' . $request['phone'];
        $data['messageSubject'] = 'iSaudavel: Acabamos de receber um lead no iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@isaudavel.com', 'Landing iSaudavel');
            $message->to('contato@maisbartenders.com.br', 'iSaudavel')->subject($data['messageSubject']);
        });

        //Email
        $data = [];
        $data['align'] = 'center';
        $data['messageTitle'] = 'Olá, ' . $request['name'];
        $data['messageOne'] = 'Obrigado por se inscrever em nossa lista, em breve você receberá informações exclusivas sobre essa novidade que estamos preparando com muito carinho para você.';
        $data['messageTwo'] = 'Nos vemos em breve!';
        $data['messageSubject'] = $request['name'] . ' obrigado por se inscrever !';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $request){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($request['email'], $request['name'])->subject($data['messageSubject']);
        });

        return 'ok';

    }

    /**
     * Show da empresa
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function showCompany($slug)
    {
        $company_fetched = Company::where('slug', $slug)->with(['public_confirmed_professionals', 'last_ratings', 'photos', 'recomendations', 'plans' => function($query){
                $query->where('is_active', 1);
                $query->where('is_starred', 1);
        }])->first();

        if($company_fetched){
            return view('landing.companies.show', compact('company_fetched'));
        }

        abort(404);
    }

    /** Show do evento
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ShowEvent($slug)
    {
        $event_fetched = Event::where('slug', $slug)->with(['participants' => function($query){
            $query->select('event_id', 'participant_id', 'participant_type');
            $query->with(['participant' => function($querydois){
                $querydois->select('id', 'name', 'full_name', 'email', 'slug');
            }]);
        }, 'comments.from', 'modality', 'submodalities'])->first();


        $companies = Company::with('categories')->limit(8)->get();

        if($event_fetched){
            return view('landing.events.show', compact('event_fetched', 'companies'));
        }

        abort(404);
    }

    /**
     * List recipes
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ListRecipes(Request $request)
    {
        $filters = is_array($request->get('filters')) ? $request->get('filters') : json_decode($request->get('filters'), true);

        $recipes = MealRecipe::whereHas('types', function($query) use ($filters){
            if(!empty($filters['types'])){
                $query->whereIn('slug', $filters['types']);
            }
        })
            ->whereHas('tags', function ($query) use ($filters) {
                if(!empty($filters['tags'])) {
                    $query->whereIn('slug', $filters['tags']);
                }

            })->where(function($query) use($filters){

                if(!empty($filters['nutrients'])) {
                    foreach($filters['nutrients'] as $key => $value){
                        if(!$value){
                            continue;
                        }
                        // - or + 20%
                        $min = $value - ( $value * 20 / 100);
                        $max = $value + ( $value * 20 / 100);

                        $query->orWhereBetween($key,[$min, $max]);
                    }
                }
            })->where(function($query) use($filters){

                if(!empty($filters['search'])){
                    $search = explode(' ', $filters['search']);
                    $query->where('title', 'LIKE', '%' . $filters['search']. '%');
                    $query->orWhereIn('title', $search);
                }
            })
            ->with(['tags' => function($query){
                $query->select('id', 'name', 'slug');
            }, 'type'])
            ->paginate(12);

        $recipes->appends(['filters' => $filters]);

        $companies = Company::with('categories')->limit(8)->get();

        $meal_types = MealType::orderBy('name')->get();

        $tags = MealRecipeTag::orderBy('name')->get();

        \JavaScript::put(['meal_types' => $meal_types, 'tags' => $tags, 'filters' => $filters]);

        return view('landing.recipes.list', compact('recipes', 'companies', 'meal_types', 'tags'));
    }

    /**
     * Show recipe
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function ShowRecipe($slug)
    {
        $recipe_fetched = MealRecipe::where('slug', $slug)->with(['from', 'comments'])->first();

        $companies = Company::with('categories')->limit(8)->get();

        if($recipe_fetched){
            return view('landing.recipes.show', compact('recipe_fetched', 'companies'));
        }

        abort(404);
    }

    /**
     * Show do profissional
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function showProfessional($slug)
    {
        $professional_fetched = Professional::where('slug', $slug)->with(['companies', 'last_ratings', 'certifications', 'categories'])->first();

        if($professional_fetched){
            return view('landing.professionals.show', compact('professional_fetched'));
        }

        abort(404);
    }

    /**
     * Criar empresa
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompany()
    {
        return view('landing.companies.create');
    }

    /**
     * Login profissional
     *
     * @return \Illuminate\Http\Response
     */
    public function showProfessionalLogin()
    {
        return view('landing.auth.login');
    }

    /**
     * View para clientes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forClientLanding()
    {
        $recipes = MealRecipe::with('from')->orderBy('created_at', 'DESC')->limit(8)->get();
        $companies = Company::with('categories')->limit(8)->get();
        $events = Event::with('categories')->limit(8)->paginate(8);
        $articles = SiteArticle::orderBy('created_at', 'DESC')->limit(8)->get();

        return view('landing.home.for-client', compact('companies', 'events', 'recipes', 'articles'));
    }

    /**
     * Show do client
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showClient($id)
    {
        $client_fetched = Client::where('id', $id)->with(['companies', 'activities'])->first();

        if ($client_fetched) {
            return view('landing.home.showclient', compact('client_fetched'));
        }

        abort(404);
    }

    /**
     * View para profissionais
     * @param Request $reques
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forProfessionalsLanding(Request $reques)
    {
        $recipes = MealRecipe::with('from')->orderBy('created_at', 'DESC')->limit(8)->get();
        $companies = Company::with('categories')->limit(8)->get();
        $events = Event::with('categories')->limit(8)->paginate(8);
        $articles = SiteArticle::orderBy('created_at', 'DESC')->limit(8)->get();

        return view('landing.home.for-professional', compact('companies', 'events', 'recipes', 'articles'));
    }

    /** Cadastro profissional (acho que será removido)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerProfessional()
    {
        return view('landing.signup.company');
    }

    /**
     * Novo cadastro do profissional (funil)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerUser()
    {
        return view('landing.signup.index');
    }

    /**
     * Store do profissional
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function sendSignupForm(Request $request)
    {
        $professional_data = json_decode($request->get('professional'), true);

        $user_password = rand(101010,999999);

        $professional_exists = Professional::where('email', $professional_data['email'])->first();

        if($professional_exists){
            flash('<strong>Atenção:</strong> este e-mail já está em uso, por favor escolha outro ou faça o login utilizando o e-mail informado')->error()->important();
            return redirect()->back()->withInput($professional_data);
        }

        //set user password
        array_set($professional_data, 'password', bcrypt($user_password));

        $professional = tap(Professional::create($professional_data))->fresh();

        $professional->categories()->attach($professional_data['categories_selected']);

        //Notify oracle
        event(new OracleNotification(['type' => 'new_professional', 'payload' => $professional]));

        //Email para cliente
        $data = [];
        $data['align'] = 'center';
        $data['messageTitle'] = 'Olá, ' . $professional->full_name;
        $data['messageOne'] = 'Obrigado por se inscrever na plataforma iSaudavel. <br>';
        $data['messageTwo'] = 'Confira abaixo os dados para acesso: <br>Usuário:  <strong>'. $professional->email .'</strong> | Senha: <strong>'. $user_password .'</strong> <br>
        <tr>
            <td style="padding: 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                align="center">
                <center>
                    <h3>Escolha um plano</h3>
                    <p>Selecione um plano e gerencie seus clientes de maneira rápida e inteligente.</p><br>
                    <a style="transition: all 100ms ease-in; display: block; font-weight: bold; text-align: center; margin: 10px 10px 10px; text-decoration: none; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                       class="button-a" align="center"
                       href="'.env('APP_URL').'/cadastro/finalizar?professional='.$professional->id.'">
                        <span style="color: rgb(255, 255, 255); border-color: #69A7BE; background-color: #69A7BE; width: 200px; height: 70px; border-radius: 5px; border-width: 5px; font-size: 20px; padding: 15px 30px 15px 30px; margin: 30px 10px;">
                            Escolher um plano
                        </span>
                    </a>
                </center>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                align="center">
                <center>
                    <h3>Possui uma empresa?</h3>
                    <p>Cadastre sua empresa e deixe que os usuários do iSaudavel encontrem você.</p><br>
                    <a style="transition: all 100ms ease-in; display: block; font-weight: bold; text-align: center; margin: 10px 10px 10px; text-decoration: none; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                       class="button-a" align="center"
                       href="'.env('APP_URL').'/cadastro/empresa?id='.$professional->id.'">
                        <span style="color: #fff; border-color: #88C657; background-color: #88C657; width: 200px; height: 70px; border-radius: 5px; border-width: 5px; font-size: 20px; padding: 15px 30px 15px 30px; margin: 30px 10px;">
                            Cadastre sua empresa agora
                        </span>
                    </a>
                </center>
            </td>
        </tr>';
        $data['messageThree'] = 'É muito importante que você altere sua senha no primeiro acesso.';
        $data['messageFour'] =  '<p>Acesse online em <a href="https://app.isaudavel.com">app.isaudavel.com</a> ou baixe o aplicativo 
            para <a href="https://play.google.com/store/apps/details?id=com.isaudavel" target="_blank">Android</a> e <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8" target="_blank">iOS (Apple)</a></p>';
        $data['messageSubject'] = $professional->full_name . ' no iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $professional){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($professional->email, $professional->full_name)->subject($data['messageSubject']);
        });

        return redirect()->route('landing.signup.success',  ['id' => $professional->id]);

    }

    /** View de agradecimento / sucesso (fase 2 funil)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerSuccess()
    {
        return view('landing.signup.confirm');
    }

    /** View adicionar empresa
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerCompany()
    {
        return view('landing.signup.company-new');
    }

    /**
     * Store da company
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendSignupCompany(Request $request)
    {
        $company_data = json_decode($request->get('company'), true);

        array_set($company_data, 'id',   Uuid::generate()->string);
        array_set($company_data, 'is_active', false);
        array_set($company_data, 'description', '');

        $company = tap(Company::create($company_data))->fresh();

        $professional = Professional::find($company->owner_id);

        $company->categories()->attach($professional->categories);

        $company->professionals()->attach($company->owner_id, [
            'is_admin' => true,
            'is_confirmed' => true,
            'confirmed_by_id' => $company->owner_id,
            'confirmed_by_type' => Professional::class,
            'confirmed_at' => Carbon::now()
        ]);

        //Notify oracle
        event(new OracleNotification(['type' => 'new_company', 'payload' => $company->load('owner')]));

        return redirect()->route('landing.signup.plan.chooser',  ['professional' => $professional->id, 'company' => $company->id]);
    }

    /**
     * View seleção do tipo do plano (fase 3 funil)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerSelectType()
    {
        return view('landing.signup.select-type');
    }

    /**
     * Termos de uso
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terms()
    {
        return view('landing.home.terms');
    }

    /**
     * Privacidade
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacy()
    {
        return view('landing.home.privacy');
    }

    /**
     * Mensagem de sucesso cadastro (acho que será removido)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signupSuccess()
    {
        return view('landing.signup.success');
    }

    /**
     * Escolha tipo de convite
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invitedChoice()
    {
        $companies = Company::with('categories')->limit()->get();

        return view('landing.invite.index', compact('companies'));
    }

    /**
     * View cliente convidado
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invitedClient()
    {
        return view('landing.signup.invited-client');
    }

    /**
     * Store cliente
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function signupClient(Request $request)
    {
        $terms = json_decode($request->get('terms'));
        $user_password = rand(101010,999999);

        $request->merge(['terms' => $terms, 'password' => bcrypt($user_password)]);

        $client_exists = Client::where('email', $request->get('email'))->first();

        if($client_exists){
            flash('<strong>Atenção:</strong> este e-mail já está em uso, por favor escolha outro ou faça o login utilizando o e-mail informado')->error()->important();
            return redirect()->back()->withInput($request->input());
        }

        $client = Client::create($request->all());

        //Email para cliente
        $data = [];
        $data['align'] = 'left';
        $data['messageTitle'] = 'Olá, ' . $request->get('name') .  ' ' . $request->get('last_name');
        $data['messageOne'] = 'Obrigado por se inscrever na plataforma iSaudavel.';
        $data['messageTwo'] = 'Confira abaixo os dados para acesso: <br>Usuário:  <strong>'. $request->get('email') .'</strong> | Senha: <strong>'. $user_password .'</strong>';
        $data['messageThree'] = 'É muito importante que você altere sua senha no primeiro acesso.';
        $data['messageFour'] = 'Nos vemos em breve!';
        $data['messageSubject'] = 'Bem-vindo ao iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $request){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($request->get('email'), $request->get('name'))->subject($data['messageSubject']);
        });

        return redirect('https://app.isaudavel.com/#/cliente/login?new=true');
    }

    /**
     * View profissional convidado
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invitedProfessional()
    {
        return view('landing.signup.invited-professional');
    }


    /**
     * Store profissional
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function signupProfessional(Request $request)
    {
        $categories = json_decode($request->get('categories'));
        $terms = json_decode($request->get('terms'));
        $user_password = rand(101010,999999);

        $request->merge(['terms' => $terms, 'password' => bcrypt($user_password)]);


        $professional_exists = Professional::where('email', $request->get('email'))->first();

        if($professional_exists){
            flash('<strong>Atenção:</strong> este e-mail já está em uso, por favor escolha outro ou faça o login utilizando o e-mail informado')->error()->important();
            return redirect()->back()->withInput($request->input());
        }

        $professional = Professional::create($request->all());

        $professional->categories()->attach($categories);

        //Email para cliente
        $data = [];
        $data['align'] = 'left';
        $data['messageTitle'] = 'Olá, ' . $request->get('name') .  ' ' . $request->get('last_name');
        $data['messageOne'] = 'Obrigado por se inscrever na plataforma iSaudavel.';
        $data['messageTwo'] = 'Confira abaixo os dados para acesso: <br>Usuário:  <strong>'. $request->get('email') .'</strong> | Senha: <strong>'. $user_password .'</strong>';
        $data['messageThree'] = 'É muito importante que você altere sua senha no primeiro acesso.';
        $data['messageFour'] = 'Nos vemos em breve!';
        $data['messageSubject'] = 'Bem-vindo ao iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $request){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($request->get('email'), $request->get('name'))->subject($data['messageSubject']);
        });

        return redirect('https://app.isaudavel.com/#/login?new=true');
    }


    /**
     * Envio do formulario de contato
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContactForm(Request $request)
    {
        //Email
        $data = [];
        $data['align'] = 'left';

        $data['messageTitle'] = 'Contato iSaudavel';
        $data['messageOne'] = 'Nome: ' . $request['name'];
        $data['messageTwo'] = 'Email: ' . $request['email'];
        $data['messageThree'] = 'Assunto: ' . $request['subject'];
        $data['messageFour'] = 'Mensagem: ' . $request['message'];
        $data['messageSubject'] = 'Contato iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@isaudavel.com', 'Landing iSaudavel');
            $message->to('contato@maisbartenders.com.br', 'iSaudavel')->subject($data['messageSubject']);
        });

        //Email
        $data = [];
        $data['align'] = 'left';
        $data['messageTitle'] = 'Olá, ' . $request['name'];
        $data['messageOne'] = 'Sua mensagem foi enviada com sucesso, em breve entraremos em contato';
        $data['messageTwo'] = 'Nos vemos em breve!';
        $data['messageSubject'] = $request['name'] . ' obrigado por entrar em contato!';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $request){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($request['email'], $request['name'])->subject($data['messageSubject']);
        });

        flash('Sua mensagem foi enviada com sucesso, em breve entraremos em contato.')->success()->important();

        return redirect()->back();
    }

}
