<?php

namespace App\Http\Controllers;

use App\Models\SubModality;
use Illuminate\Http\Request;

class SubModalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_modalities = SubModality::paginate(10);

        return response()->json(custom_paginator($sub_modalities));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sub_modality = SubModality::create($request->all());

        return response()->json([
            'message' => 'Sub modality created.',
            'submodality' => $sub_modality->fresh()
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
        $sub_modality = SubModality::find($id);

        return response()->json(['data' => $sub_modality]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $sub_modality = tap(SubModality::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Sub modality updated.',
            'submodality' => $sub_modality
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $destroyed = SubModality::destroy($request->get('id'));

        if($destroyed){
            return response()->json([
                'message' => 'Sub modality destroyed.',
            ]);
        }

        return response()->json([
            'message' => 'Sub modality not found.',
        ], 404);

    }

}
