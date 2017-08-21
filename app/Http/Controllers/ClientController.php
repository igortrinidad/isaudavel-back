<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientPhoto;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($clients));
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companyClients(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $per_page = 10;

        $clients = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        })->orderBy('name')->get();

        $verified_clients = [];
        foreach ($clients as $client){
            //check if is a company client
            $is_client = $client->companies->contains($request->get('company_id'));

            //check if is confirmed
            $is_confirmed = $client->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_confirmed', '=', true)->count();

            $requested_by_client = $client->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('requested_by_client', '=', true)->count();

            $client['is_client'] = $is_client ? true : false;
            $client['is_confirmed'] = $is_confirmed ? true : false;
            $client['requested_by_client'] = $requested_by_client ? true : false;
            $verified_clients[] = $client->setHidden(['companies']);
        }

        $verified_clients = collect($verified_clients);

        $currentPageItems = $verified_clients->slice(($currentPage - 1) * $per_page, $per_page);

        $paged_clients =  new LengthAwarePaginator($currentPageItems->flatten(), count($verified_clients), $per_page);

        return response()->json(custom_paginator($paged_clients, 'clients'));
    }

    /**
     * Client Search.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $per_page = 10;

        $search = explode(' ', $request->get('search'));

        $clients = Client::where(function ($query) use ($search, $request) {
            $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

            //for full name
            $query->orWhereIn('name', $search);
            $query->orWhere(function ($query) use ($search) {
                $query->whereIn('last_name', $search);
            });

        })->get();

        $verified_clients = [];
        foreach ($clients as $client){
            //check if is a company client
            $is_client = $client->companies->contains($request->get('company_id'));

            //check if is confirmed
            $is_confirmed = $client->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_confirmed', '=', true)->count();

            $requested_by_client = $client->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('requested_by_client', '=', true)->count();

            $client['is_client'] = $is_client ? true : false;
            $client['is_confirmed'] = $is_confirmed ? true : false;
            $client['requested_by_client'] = $requested_by_client ? true : false;
            $verified_clients[] = $client->setHidden(['companies']);
        }

        $verified_clients = collect($verified_clients);

        $currentPageItems = $verified_clients->slice(($currentPage - 1) * $per_page, $per_page);

        $paged_clients =  new LengthAwarePaginator($currentPageItems->flatten(), count($verified_clients), $per_page);

        return response()->json(custom_paginator($paged_clients, 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //see cause this dont work inside model create

        $checkClient = Client::where('email', $request->get('email'))->first();

        if($checkClient){
            return response()->json([
                'message' => 'Client already exist.',
                'status' => 422
            ]);
        }

        $request['bday'] = Carbon::createFromFormat('d/m/Y', $request['bday'])->toDateString();

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);


        $client = Client::create($request->all());

        //If is a company creating a client attach automatically
        if($request->has('company_id') && $request->get('company_id')){
            $client->companies()->attach($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now(),
                    'trainnings_show' => true,
                    'trainnings_edit' => true,
                    'diets_show' => true,
                    'diets_edit' => true,
                    'evaluations_show' => true,
                    'evaluations_edit' => true,
                    'restrictions_show' => true,
                    'restrictions_edit' => true,
                    'exams_show' => true,
                    'exams_edit' => true,
                ]);
        }

        return response()->json([
            'message' => 'Client created.',
            'client' => $client->fresh(['photos'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $client = Client::find($request->get('client_id'))->load(['photos', 'subscriptions.plan', 'subscriptions.invoices']);

        return response()->json(['client' => $client]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function showCompany(Request $request)
    {

        $client = Client::with(['subscriptions' => function($query) use ($request){
            $query->with(['plan', 'invoices']);
            $query->where('company_id',  '=', $request->get('company_id'));
            $query->orderBy('is_active', 'DESC');
            $query->orderBy('updated_at', 'DESC');
        }])->find($request->get('client_id'));

        return response()->json(['client' => $client]);
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

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                ClientPhoto::find($photo['id'])->update($photo);
            }
        }

        $client = tap(Client::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Client updated.',
            'client' => $client
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
        $destroyed = Client::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Client destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Client not found.',
        ], 404);

    }

    /**
     * Company requests client solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companySolicitation(Request $request)
    {
        $requested_by_client = $request->get('requested_by_client');

        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->attach($request->get('company_id'), ['is_confirmed' => false, 'requested_by_client' => $requested_by_client]);

            //load relation to return
            $client_company = $client->companies()->select('id', 'name', 'slug')
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->withPivot('is_confirmed', 'requested_by_client')
                ->first();

            return response()->json(['message' => 'OK', 'company' => $client_company]);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }

    /**
     *  Client accept company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function acceptCompanySolicitation(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->updateExistingPivot($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now()
                ]);


            //load relation to return
            $client_company = $client->companies()->select('id', 'name', 'slug')
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->withPivot('is_confirmed', 'requested_by_client')
                ->first();

            return response()->json(['message' => 'OK', 'company' => $client_company]);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }


    /**
     *  Client remove company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeCompanySolicitation(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->detach($request->get('company_id'));

            return response()->json(['message' => 'OK']);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }

    /**
     *  Update client company relationship
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateCompanyRelationship(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->updateExistingPivot($request->get('company_id'), $request->all());

            return response()->json(['message' => 'Relationship updated']);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }


}
