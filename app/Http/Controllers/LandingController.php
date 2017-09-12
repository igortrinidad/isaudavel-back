<?php

namespace App\Http\Controllers;

use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Lead;

use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Models\Client;
use App\Models\Professional;
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
        $companies = Company::with('categories')->get();
        $events = Event::with('categories')->get();

        return view('landing.home.home', compact('companies', 'events'));
    }

    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ListEvents(Request $request)
    {
        $events = Event::with('categories')->get();
        $companies = Company::with('categories')->limit(8)->get();

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
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function NewIndexSearch(Request $request)
    {

        $categories = Category::all();

        if( empty($request->query('lat')) && empty($request->query('lng')) ) {

            $companies = Company::with('categories')->
                whereHas('categories', function($query) use($request){
                    $query->where('slug', 'LIKE', '%'.$request->query('category') . '%');
            })->paginate(30);

        }


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
                }])->where('name', 'LIKE', '%' . $request->get('search') . '%' )
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

        return view('landing.companies.list', compact('companies', 'categories'));
    }

    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showCompany($slug)
    {
        $company_fetched = Company::where('slug', $slug)->with(['professionals', 'last_ratings', 'photos', 'recomendations', 'plans'])->first();

        if($company_fetched){
            return view('landing.companies.show', compact('company_fetched'));
        }

        abort(404);
    }

    public function ShowEvent($slug)
    {
        $event_fetched = Event::where('slug', $slug)->with(['participants' => function($query){
            $query->select('event_id', 'participant_id', 'participant_type');
            $query->with(['participant' => function($querydois){
                $querydois->select('id', 'name', 'full_name', 'email', 'slug');
            }]);
        }, 'comments.from'])->first();


        $companies = Company::with('categories')->limit(8)->get();

        if($event_fetched){
            return view('landing.events.show', compact('event_fetched', 'companies'));
        }

        abort(404);
    }
    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showProfessional($id)
    {
        $professional_fetched = Professional::where('id', $id)->with(['companies', 'last_ratings', 'certifications', 'categories'])->first();

        if($professional_fetched){
            return view('landing.companies.showprofessional', compact('professional_fetched'));
        }

        abort(404);
    }

    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createCompany()
    {
        return view('landing.companies.create');
    }

        /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showProfessionalLogin()
    {
        return view('landing.auth.login');
    }

    // PS: Não sei se é o correto mas assim functionou haha
    public function forClientLanding(Request $reques)
    {
        $companies = Company::with('categories')->limit(8)->get();
        $events = Event::with('categories')->limit(8)->get();

        return view('landing.home.for-client', compact('companies', 'events'));
    }

    public function showClient($id)
    {
        $client_fetched = Client::where('id', $id)->with(['companies', 'activities'])->first();

        if ($client_fetched) {
            return view('landing.home.showclient', compact('client_fetched'));
        }

        abort(404);
    }

    public function forProfessionalsLanding(Request $reques)
    {
        $companies = Company::with('categories')->limit(8)->get();
        $events = Event::with('categories')->limit(8)->get();

        return view('landing.home.for-professional', compact('companies', 'events'));
    }

    public function registerProfessional()
    {

        return view('landing.signup.company');
    }

    public function sendSignupForm(Request $request)
    {

        $address = json_decode($request->get('address'));
        $categories = json_decode($request->get('categories'));
        $terms = json_decode($request->get('terms'));

        $user_password = Str::random(6);

        $professional_exists = Professional::where('email', $request->get('email'))->first();

        if($professional_exists){
            flash('<strong>Atenção:</strong> este e-mail já está em uso, por favor escolha outro ou faça o login utilizando o e-mail informado')->error()->important();
            return redirect()->back()->withInput($request->input());
        }

        $professional_data = [
            'name' => $request->get('name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'slug' => $request->get('slug_professional'),
            'cpf' => $request->get('cpf'),
            'phone' => $request->get('phone'),
            'password' => bcrypt($user_password),
            'terms' => $terms
        ];


        $professional = Professional::create($professional_data);

        $professional->categories()->attach($categories);

        $company_data = [
            'id' => Uuid::generate()->string,
            'owner_id' => $professional->id,
            'is_active' => false,
            'name' => $request->get('company_name'),
            'slug' => $request->get('slug'),
            'website' => $request->get('website'),
            'phone' => $request->get('phone'),
            'description' => '',
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'address' => $address,
            'terms' => $terms
        ];


        $company = Company::create($company_data);

        $company->categories()->attach($categories);

        $company->professionals()->attach($professional->id, [
            'is_admin' => true,
            'is_confirmed' => true,
            'confirmed_by_id' => $professional->id,
            'confirmed_by_type' => get_class($professional),
            'confirmed_at' => Carbon::now()
        ]);

        $subscription_data = [
            'company_id' => $company->id,
            'professionals' => $request->get('professionals'),
            'categories' => count($categories),
            'total' => $request->get('total'),
            'is_active' => false,
            'start_at' => Carbon::now()->format('d/m/Y'),
            'expire_at' => Carbon::now()->addMonth(1)->format('d/m/Y')
        ];

        $company_subscription = CompanySubscription::create($subscription_data);

        // Company Invoice
        $invoice_items = [
            [
                'description' => 'Especialidades da empresa',
                'item' => 'categories',
                'quantity' => $company_subscription->categories,
                'total' => ($company_subscription->categories * 37.90) ,
                'is_partial' => false,
                'reference' => 'Referente ao período de '.  Carbon::now()->format('d/m/Y').' à '.Carbon::now()->addMonth(1)->format('d/m/Y')
            ],
            [
                'description' => 'Profissionais da empresa',
                'item' => 'professionals',
                'quantity' => $company_subscription->professionals,
                'total' => (($company_subscription->professionals - 1) * 17.90),
                'is_partial' => false,
                'reference' => 'Referente ao período de '.  Carbon::now()->format('d/m/Y').' à '.Carbon::now()->addMonth(1)->format('d/m/Y')
            ],

        ];

        $invoice_history = [
            [
                'full_name' =>'Sistema iSaudavel',
                'action' => 'invoice-created',
                'label' => 'Fatura gerada',
                'date' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        $invoice = CompanyInvoice::create([
            'company_id' => $company->id,
            'subscription_id' => $company_subscription->id,
            'total' => $company_subscription->total,
            'expire_at' => $company_subscription->expire_at,
            'items' => $invoice_items,
            'history' => $invoice_history,
        ]);


        //Email para nós
        $data = [];
        $data['align'] = 'left';

        $data['messageTitle'] = 'Novo cadastro iSaudavel';
        $data['messageOne'] = 'Nome: ' . $request->get('name') .  ' ' . $request->get('last_name');
        $data['messageTwo'] = 'Email: ' . $request->get('email');
        $data['messageThree'] = 'Phone: ' . $request->get('phone');
        $data['messageSubject'] = 'iSaudavel nova empresa: ' . $request->get('company_name');

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@isaudavel.com', 'Landing iSaudavel');
            $message->to('contato@maisbartenders.com.br', 'iSaudavel')->subject($data['messageSubject']);
        });

        //Email para cliente
        $data = [];
        $data['align'] = 'center';
        $data['messageTitle'] = 'Olá, ' . $request->get('name') .  ' ' . $request->get('last_name');
        $data['messageOne'] = 'Obrigado por se inscrever na plataforma iSaudavel. <br>
            Vamos processar suas informações e retornamos este email com os próximos passos para habilitar sua empresa na plataforma fitness mais completa do mundo.
        ';
        $data['messageTwo'] = 'Confira abaixo os dados para acesso: <br>Usuário:  <strong>'. $request->get('email') .'</strong> | Senha: <strong>'. $user_password .'</strong>';
        $data['messageThree'] = 'É muito importante que você altere sua senha no primeiro acesso.';
        $data['messageFour'] = 'Nos vemos em breve!';
        $data['messageSubject'] = $request->get('company_name') . ' no iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $request){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($request->get('email'), $request->get('name'))->subject($data['messageSubject']);
        });


        return redirect('https://app.isaudavel.com/#/login?new=true');
    }



    public function terms()
    {
        return view('landing.home.terms');
    }

    public function privacy()
    {
        return view('landing.home.privacy');
    }

    public function signupSuccess()
    {
        return view('landing.signup.success');
    }

    public function invitedChoice(Request $reques)
    {
        $companies = Company::with('categories')->limit()->get();

        return view('landing.invite.index', compact('companies'));
    }

    public function invitedClient()
    {
        return view('landing.signup.invited-client');
    }

    public function signupClient(Request $request)
    {
        $terms = json_decode($request->get('terms'));
        $user_password = Str::random(6);

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

    public function invitedProfessional()
    {
        return view('landing.signup.invited-professional');
    }

    public function signupProfessional(Request $request)
    {
        $categories = json_decode($request->get('categories'));
        $terms = json_decode($request->get('terms'));
        $user_password = Str::random(6);

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
