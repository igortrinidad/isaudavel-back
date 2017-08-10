<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Professional;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professionals = Professional::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($professionals));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);

        $professional = Professional::create($request->all());

        return response()->json([
            'message' => 'Professional created.',
            'professional' => $professional->fresh(['photos'])
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
        $professional = Professional::with(['photos', 'categories', 'companies'])->find($id);

        return response()->json(['professional' => $professional]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('password') && !empty($request['password'])){
            $request->merge([
                'password' => bcrypt($request->get('password')),
            ]);
        }

        $professional = tap(Professional::find($request->get('id')))->update($request->all())->fresh();

        // Detach categories
        $professional->categories()->detach();
        // attach categories
        $professional->categories()->attach($request->get('categories'));

        return response()->json([
            'message' => 'Professional updated.',
            'professional' => $professional
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
        $destroyed = Professional::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Professional destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Professional not found.',
        ], 404);

    }

    /**
     * Search professionals by given category
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByCategory(Request $request)
    {
        $professionals = Professional::whereHas('categories', function($query) use($request){

            if($request->get('category') === 'all'){
                $query->where('slug', '<>', 'all');
            }

            if($request->get('category') != 'all'){
                $query->where('slug', $request->get('category'));
            }

        })->with(['categories' => function($query){
            $query->select('name');
        }])->orderBy('name', 'asc')->get();

        return response()->json(['count' => $professionals->count(), 'data' => $professionals]);
    }
}
