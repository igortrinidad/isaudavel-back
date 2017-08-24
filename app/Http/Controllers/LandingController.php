<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Lead;

use App\Models\Category;
use App\Models\Company;
use App\Models\Professional;

class LandingController extends Controller
{


    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('prelaunch.index');

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
        $data['align'] = 'left';

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
        $data['align'] = 'left';
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
     * New Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function NewIndex(Request $request)
    {
        $companies = Company::with('categories')->get()->random(8);

        return view('landing.home.index', compact('companies'));
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
                    $query->where('name', 'LIKE', '%'.$request->query('category') . '%');
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
        $company_fetched = Company::where('slug', $slug)->with(['professionals', 'last_ratings', 'photos', 'recomendations'])->first();

        if($company_fetched){
            return view('landing.companies.show', compact('company_fetched'));   
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
        $professional_fetched = Professional::where('id', $id)->with(['companies', 'last_ratings', 'certifications'])->first();

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
}

