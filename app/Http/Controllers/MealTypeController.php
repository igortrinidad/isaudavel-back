<?php

namespace App\Http\Controllers;

use App\Models\MealType;
use Illuminate\Http\Request;

class MealTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meal_types = MealType::paginate(10);

        return response()->json(custom_paginator($meal_types));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $meal_type = MealType::create($request->all());

        return response()->json([
            'message' => 'Meal type created.',
            'meal_type' => $meal_type->fresh()
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
        $meal_type = MealType::find($id);

        return response()->json(['data' => $meal_type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $meal_type = tap(MealType::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Meal type updated.',
            'meal_type' => $meal_type
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
        $destroyed = MealType::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Meal type destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Meal type not found.',
        ], 404);

    }

    /**
     * Display a listing of the resource for select.
     *
     * @return \Illuminate\Http\Response
     */
    public function forSelect()
    {
        $meal_types = MealType::select('id', 'name', 'slug')->orderBy('name')->get();

        return response()->json($meal_types);
    }
}
