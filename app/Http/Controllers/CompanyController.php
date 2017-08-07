<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::create($request->all());

        return response()->json([
            'message' => 'Company created.',
            'company' => $company->fresh(['photos'])
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
        $company = Company::find($id);

        return response()->json(['data' => $company]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $company = tap(Company::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Company updated.',
            'company' => $company
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
     * Search companies by given location
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByLocation(Request $request)
    {
        $user_lat = $request->get('lat');
        $user_lng = $request->get('lng');

        $companies = \App\Models\Company::select(\DB::raw("*, 
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
            }])->whereHas('categories', function($query) use($request){

                if($request->get('category') === 'all'){
                    $query->where('slug', '<>', 'all');
                }

                if($request->get('category') != 'all'){
                    $query->where('slug', $request->get('category'));
                }

            })->with(['categories' => function($query){
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
}