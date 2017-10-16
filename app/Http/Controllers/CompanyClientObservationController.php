<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanyClientObservation;
use Illuminate\Http\Request;

class CompanyClientObservationController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $observations = CompanyClientObservation::where('client_id', $request->get('client_id'))
            ->where('company_id', $request->get('company_id'))
            ->with('professional')
            ->orderBy('created_at', 'DESC')
            ->paginate(12);

        return response()->json(custom_paginator($observations));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $observation = CompanyClientObservation::create($request->all());

        $response = [
            'message' => 'Client observation created.',
            'observation'    => $observation->fresh()->toArray(),
        ];

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $observation = tap(CompanyClientObservation::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Client observation updated.',
            'observation' => $observation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $observation = CompanyClientObservation::find($id);

        $destroyed = $observation->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Client observation destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Client observation not found.',
        ], 404);
    }
}
