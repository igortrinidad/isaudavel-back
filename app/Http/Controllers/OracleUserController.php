<?php

namespace App\Http\Controllers;

use App\Models\OracleUser;
use Illuminate\Http\Request;

class OracleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oracles = OracleUser::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($oracles));
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

        $oracle = OracleUser::create($request->all());

        return response()->json([
            'message' => 'Oracle created.',
            'client' => $oracle
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
        $oracle = OracleUser::find($id);

        return response()->json(['data' => $oracle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('password')){
            $request->merge([
                'password' => bcrypt($request->get('password')),
            ]);
        }

        $oracle = tap(OracleUser::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Oracle updated.',
            'client' => $oracle
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
        $destroyed = OracleUser::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Oracle destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Oracle not found.',
        ], 404);

    }
}
