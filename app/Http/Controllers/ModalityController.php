<?php

namespace App\Http\Controllers;

use App\Models\Modality;
use Illuminate\Http\Request;

class ModalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modalities = Modality::paginate(10);

        return response()->json(custom_paginator($modalities));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $modality = Modality::create($request->all());

        return response()->json([
            'message' => 'Modality created.',
            'category' => $modality->fresh()
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
        $modality = Modality::find($id);

        return response()->json(['data' => $modality]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $modality = tap(Modality::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Modality updated.',
            'category' => $modality
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
        $destroyed = Modality::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Modality destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Modality not found.',
        ], 404);

    }

    /**
     * Display a listing of the resource for select.
     *
     * @return \Illuminate\Http\Response
     */
    public function forSelect()
    {
        $modalities = Modality::select('id', 'name', 'slug')->with(['sub_modalities' => function($query){
            $query->select('id','modality_id', 'name', 'slug');
        }])->get();

        return response()->json($modalities);
    }
}
