<?php

namespace App\Http\Controllers;

use App\Events\OracleNotification;
use App\Models\Company;
use App\Models\CompanyCalendarSettings;
use App\Models\CompanyPhoto;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($companies));
    }

    /**
     * Display a listing companies owned by professional.
     *
     * @return \Illuminate\Http\Response
     */
    public function professionalCompanies()
    {
        $companies = Company::whereHas('professionals', function ($query){
            $query->where('professional_id', \Auth::user()->id);
        })->with(['categories' => function($query){
            $query->select('id', 'name', 'slug');
        }, 'professionals.categories'])->get();

        return response()->json(['companies' => $companies]);
    }

    /**
     * Display a listing with client companies paged
     *
     * @return \Illuminate\Http\Response
     */
    public function clientCompanies()
    {
        $companies_confirmed = \Auth::user()->companies()->with(['categories'])
            ->wherePivot('is_confirmed', true)
            ->wherePivot('is_deleted', false)
            ->orderBy('name')->paginate(10);

            $companies_unconfirmed = \Auth::user()->companies()->with(['categories'])
            ->wherePivot('is_confirmed', false)
            ->wherePivot('is_deleted', false)
            ->orderBy('name')->paginate(10);

            $companies_deleted = \Auth::user()->companies()->with(['categories'])
            ->wherePivot('is_deleted', true)
            ->wherePivot('is_confirmed', false)
            ->orderBy('name')->paginate(10);

        return response()->json([
            'companies_confirmed' => custom_paginator($companies_confirmed, 'companies_confirmed'),
            'companies_unconfirmed' => custom_paginator($companies_unconfirmed, 'companies_unconfirmed'),
            'companies_deleted' => custom_paginator($companies_deleted, 'companies_deleted'),
        ]);
    }

    /**
     * Display a listing wuth all client companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function companiesFullList()
    {
        $companies = \Auth::user()->companies()->select('id', 'name', 'slug')
            ->with(['categories' => function($query){
                $query->select('id', 'name', 'slug');
            }])->withPivot('is_confirmed', 'requested_by_client')->orderBy('name')->get();

        return response()->json(['companies' => $companies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::create($request->all());

        // attach categories
        $company->categories()->attach($request->get('categories'));

        //attach owner as professional with admin rights
        $company->professionals()->attach($request->get('owner_id'), ['is_admin' => true, 'is_confirmed' => true]);

        // Create the callendar settings
        factory(CompanyCalendarSettings::class)->create([
            'company_id' => $company->id,
        ]);

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                CompanyPhoto::find($photo['id'])->update($photo);
            }
        }

        //Notify oracle
        event(new OracleNotification(['type' => 'new_company', 'payload' => $company->load('owner')]));

        return response()->json([
            'message' => 'Company created.',
            'company' => $company->fresh(['photos', 'professionals', 'categories']),
            'company_user' => \Auth::user()->companies->where('id', $company->id)->first()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::with(['professionals.categories', 'professionals.companies', 'photos', 'clients', 'categories.calendar_settings'])->find($id);

        return response()->json(['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show_public($slug)
    {
        $company = Company::with(['public_confirmed_professionals.categories', 'photos', 'categories', 'plans' => function($query){
                $query->where('is_active', 1);
                $query->where('is_starred', 1);
        }, 'last_ratings'])->where('slug', $slug)->first();

        return response()->json(['company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $company = tap(Company::find($request->get('id')))->update($request->all());

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                CompanyPhoto::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'Company updated.',
            'company' => $company->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = Company::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Company destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Company not found.',
        ], 404);

    }

    /**
     * Search companies by given location for the app
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByLocation(Request $request)
    {
        $user_lat = $request->get('lat');
        $user_lng = $request->get('lng');

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
            }])
            ->where('name', 'LIKE', '%' . $request->get('search') . '%' )
                ->whereHas('categories', function($query) use($request){

                    if($request->get('category') === 'all'){
                        $query->where('slug', '<>', 'all');
                    }

                    if($request->get('category') != 'all'){
                        $query->where('slug', $request->get('category'));
                    }

            })
            ->where('is_active', 1)
            ->orderBy('is_paid', 'DESC')
            ->with(['categories' => function($query){
                $query->select('name');
            }])->orderBy('distance_m', 'asc')
            ->get();



        //format response
        $nearby_companies = $companies->map(function ($item, $key) {

            // meter to km
            $distance_km = round(($item->distance_m / 1000) , 2);

            $item = collect($item);

            //add fields on item
            $item->put('distance_km', $distance_km);

            return $item->all();
        });

        $nearby_companies = $nearby_companies->all();

        return response()->json(['count' => $companies->count(), 'data' => $nearby_companies]);
    }

    /**
     * Search companies by given category
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByCategory(Request $request)
    {
        $companies = Company::whereHas('categories', function($query) use($request){

            if($request->get('category') === 'all'){
                $query->where('slug', '<>', 'all');
            }

            if($request->get('category') != 'all'){
                $query->where('slug', $request->get('category'));
            }

        })->with(['categories' => function($query){
            $query->select('name');
        }])
        ->with(['professionals' => function($query){
            $query->select('id', 'name', 'last_name')
                ->with(['categories' => function($query){
                    $query->select('name');
                }])->orderBy('name', 'asc');
        }])->orderBy('name', 'asc')->get();

        return response()->json(['count' => $companies->count(), 'data' => $companies]);
    }

        /**
     *  Check Slug
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function check_slug($slug)
    {
        $company = Company::where('slug', $slug)->first();

        if($company){
            $already_exist = true;
        } else {
            $already_exist = false;
        }

        return response()->json([
            'already_exist' => $already_exist,
        ], 200);
    }

    /**
     * Display a client company
     *
     * @param $company_id
     * @return \Illuminate\Http\Response
     */
    public function showClient($company_id)
    {
        $company = \Auth::user()->companies()
            ->with([
                'categories',
                'public_confirmed_professionals.categories',
                'photos'
            ])
            ->wherePivot('company_id', $company_id)
            ->first();

        if(!$company) {
            return response()->json([
                'message' => 'Company not found.',
            ], 404);
        }

        return response()->json(['company' => $company]);
    }
}
