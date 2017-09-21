<?php

namespace App\Http\Controllers;

use App\Models\MealRecipeTag;
use Illuminate\Http\Request;

class MealRecipeTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = MealRecipeTag::paginate(10);

        return response()->json(custom_paginator($tags));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slug = str_slug($request->get('name'));

        $tag = MealRecipeTag::firstOrCreate(['slug' => $slug],['name' => $request->get('name'),'slug' => $slug]);

        return response()->json([
            'message' => 'Meal recipe tag created.',
            'tag' => $tag->fresh()
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
        $tag = MealRecipeTag::find($id);

        return response()->json(['data' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $tag = tap(MealRecipeTag::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Meal recipe tag updated.',
            'tag' => $tag
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
        $destroyed = MealRecipeTag::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Meal recipe tag destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Meal recipe tag not found.',
        ], 404);

    }

    /**
     * Display a listing of the resource for select.
     *
     * @return \Illuminate\Http\Response
     */
    public function forSelect()
    {
        $tags = MealRecipeTag::select('id', 'name', 'slug')->orderBy('name')->get();

        return response()->json($tags);
    }
}
