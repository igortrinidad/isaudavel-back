<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientPhoto;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);

        $client = Client::create($request->all());

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
    public function show($id)
    {

        $client = Client::find($id)->load(['photos']);

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
}
