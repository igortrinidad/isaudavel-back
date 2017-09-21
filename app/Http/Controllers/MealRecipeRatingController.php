<?php

namespace App\Http\Controllers;


use App\Models\MealRecipeRating;
use Illuminate\Http\Request;

class MealRecipeRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ratings = MealRecipeRating::where('meal_recipe_id', $request->get('meal_recipe_id'))->paginate(10);

        return response()->json(custom_paginator($ratings));
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

        $rating = MealRecipeRating::create($request->all());

        return response()->json([
            'message' => 'Rating created.',
            'comment' => $rating->fresh()
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
        $rating = MealRecipeRating::find($id);

        return response()->json(['data' => $rating]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = MealRecipeRating::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Rating destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Rating not found.',
        ], 404);

    }
}
