<?php

namespace App\Http\Controllers;

use App\Models\MealRecipe;
use Illuminate\Http\Request;

class MealRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meal_recipes = MealRecipe::paginate(10);

        return response()->json(custom_paginator($meal_recipes));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByType(Request $request)
    {
        $meal_recipes = MealRecipe::whereHas('type', function($query) use ($request){
            $query->where('slug', $request->get('slug'));
        })->with(['photos', 'tags', 'comments', 'ratings'])->get();

        return response()->json(['count' => $meal_recipes->count(), 'data' => $meal_recipes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['created_by_id' => \Auth::user()->id, 'created_by_type' => get_class(\Auth::user())]);

        $meal_recipe = MealRecipe::create($request->all());

        return response()->json([
            'message' => 'Meal type created.',
            'meal_recipe' => $meal_recipe->fresh()
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
        $meal_recipe = MealRecipe::find($id);

        return response()->json(['data' => $meal_recipe]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $meal_recipe = tap(MealRecipe::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Meal recipe updated.',
            'meal_recipe' => $meal_recipe
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
        $destroyed = MealRecipe::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Meal recipe destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Meal recipe not found.',
        ], 404);

    }
}
