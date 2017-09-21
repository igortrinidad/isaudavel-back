<?php

namespace App\Http\Controllers;

use App\Models\MealRecipeComment;
use Illuminate\Http\Request;

class MealRecipeCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments = MealRecipeComment::where('meal_recipe_id', $request->get('meal_recipe_id'))->paginate(10);

        return response()->json(custom_paginator($comments));
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

        $comment = MealRecipeComment::create($request->all());

        return response()->json([
            'message' => 'Comment created.',
            'comment' => $comment->fresh()
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
        $comment = MealRecipeComment::find($id);

        return response()->json(['data' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $comment = tap(MealRecipeComment::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Comment updated.',
            'comment' => $comment
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
        $destroyed = MealRecipeComment::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Comment destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Comment not found.',
        ], 404);

    }
}
