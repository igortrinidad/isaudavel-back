<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Professional;
use App\Models\ProfessionalPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professionals = Professional::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($professionals));
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companyProfessionals(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $per_page = 10;

        $professionals = Professional::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        })->orderBy('name')->get();



        $verified_professionals = [];
        foreach ($professionals as $professional){
            //check if is a company professional
            $is_professional = $professional->companies->contains($request->get('company_id'));

            //check if is confirmed
            $is_confirmed = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_confirmed', '=', true)->count();

            $is_admin = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_admin', '=', true)->count();

            $professional['is_professional'] = $is_professional;
            $professional['is_confirmed'] = $is_confirmed ? true : false;
            $professional['is_admin'] = $is_admin ? true : false;
            $verified_professionals[] = $professional->setHidden(['companies']);
        }

        $verified_professionals = collect($verified_professionals);

        $currentPageItems = $verified_professionals->slice(($currentPage - 1) * $per_page, $per_page);

        $paged_professionals =  new LengthAwarePaginator($currentPageItems->flatten(), count($verified_professionals), $per_page);

        return response()->json(custom_paginator($paged_professionals, 'professionals'));
    }


    /**
     * Professional Search.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $per_page = 10;

        $search = explode(' ', $request->get('search'));

        $professionals = Professional::where(function ($query) use ($search, $request) {
            $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

            //for full name
            $query->orWhereIn('name', $search);
            $query->orWhere(function ($query) use ($search) {
                $query->whereIn('last_name', $search);
            });

        })->orderBy('name')->get();

        $verified_professionals = [];
        foreach ($professionals as $professional){
            //check if is a company professional
            $is_professional = $professional->companies->contains($request->get('company_id'));

            //check if is confirmed
            $is_confirmed = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_confirmed', '=', true)->count();

            $is_admin = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_admin', '=', true)->count();

            $professional['is_professional'] = $is_professional;
            $professional['is_confirmed'] = $is_confirmed ? true : false;
            $professional['is_admin'] = $is_admin ? true : false;
            $verified_professionals[] = $professional->setHidden(['companies']);
        }

        $verified_professionals = collect($verified_professionals);

        $currentPageItems = $verified_professionals->slice(($currentPage - 1) * $per_page, $per_page);

        $paged_professionals =  new LengthAwarePaginator($currentPageItems->flatten(), count($verified_professionals), $per_page);

        return response()->json(custom_paginator($paged_professionals, 'professionals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkProfessional = Professional::where('email', $request->get('email'))->first();

        if($checkProfessional){
            return response()->json([
                'message' => 'Professional already exist.',
                'status' => 422
            ]);
        }

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);

        $professional = Professional::create($request->all());


        //Attach professional categories
        $professional->categories()->attach($request->get('categories'));

        //If is a company creating a professional attach automatically
        if($request->has('company_id') && $request->get('company_id')){
            $professional->companies()->attach($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now()
                ]);
        }

        return response()->json([
            'message' => 'Professional created.',
            'professional' => $professional->fresh(['photos'])
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
        $professional = Professional::with(['photos', 'categories', 'companies', 'certifications'])->find($id);

        return response()->json(['professional' => $professional]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('password') && !empty($request['password'])){
            $request->merge([
                'password' => bcrypt($request->get('password')),
            ]);
        }

        $professional = tap(Professional::find($request->get('id')))->update($request->all());

        // Sync categories
        $professional->categories()->sync($request->get('categories'));

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                ProfessionalPhoto::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'Professional updated.',
            'professional' => $professional->fresh()
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
        $destroyed = Professional::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Professional destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Professional not found.',
        ], 404);

    }

    /**
     * Search professionals by given category
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByCategory(Request $request)
    {
        $professionals = Professional::whereHas('categories', function($query) use($request){

            if($request->get('category') === 'all'){
                $query->where('slug', '<>', 'all');
            }

            if($request->get('category') != 'all'){
                $query->where('slug', $request->get('category'));
            }

        })->with(['categories' => function($query){
            $query->select('name');
        }])->orderBy('name', 'asc')->get();

        return response()->json(['count' => $professionals->count(), 'data' => $professionals]);
    }

    /**
     * Company requests professional solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companySolicitation(Request $request)
    {
        $professional = Professional::find($request->get('professional_id'));

        if($professional){

            $professional->companies()->attach($request->get('company_id'), ['is_confirmed' => false]);

            return response()->json(['message' => 'OK']);
        }

        if(!$professional){
            return response()->json([
                'message' => 'Professional not found.',
            ], 404);
        }

    }

    /**
     *  Professional accept company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function acceptCompanySolicitation(Request $request)
    {
        $professional = Professional::find($request->get('professional_id'));

        if($professional){



            $professional->companies()->updateExistingPivot($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now()
                ]);

            return response()->json(['message' => 'OK']);
        }

        if(!$professional){
            return response()->json([
                'message' => 'Professional not found.',
            ], 404);
        }

    }

    /**
     *  Professional remove company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeCompanySolicitation(Request $request)
    {
        $professional = Professional::find($request->get('professional_id'));

        if($professional){

            $professional->companies()->detach($request->get('company_id'));

            return response()->json(['message' => 'OK']);
        }

        if(!$professional){
            return response()->json([
                'message' => 'Professional not found.',
            ], 404);
        }

    }
}
