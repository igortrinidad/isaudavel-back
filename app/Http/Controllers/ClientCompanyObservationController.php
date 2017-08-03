<?php

namespace App\Http\Controllers;

use App\Models\ClientCompanyObservation;
use Illuminate\Http\Request;

class ClientCompanyObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $observations = ClientCompanyObservation::with('client', 'from')->paginate(10);

        return response()->json(custom_paginator($observations));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $observation = ClientCompanyObservation::create($request->all());

        return response()->json([
            'message' => 'Observation created.',
            'observation' => $observation->fresh(['client', 'from'])
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
        $observation = ClientCompanyObservation::find($id);

        return response()->json(['data' => $observation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $observation = tap(ClientCompanyObservation::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Observation updated.',
            'observation' => $observation
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
        $destroyed = Activity::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Observation destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Observation not found.',
        ], 404);

    }
}
