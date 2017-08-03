<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = Category::paginate(10);

        return response()->json(custom_paginator($activities));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = Category::create($request->all());

        return response()->json([
            'message' => 'Category created.',
            'category' => $category->fresh()
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
        $category = Category::find($id);

        return response()->json(['data' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = tap(Category::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Category updated.',
            'category' => $category
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
        $destroyed = Category::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Category destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Category not found.',
        ], 404);

    }
}
