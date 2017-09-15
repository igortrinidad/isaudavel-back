<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function client_list($id)
    {
        $activities = Activity::where('client_id', $id)->with(['about', 'user'])->paginate(20);

        return response()->json(custom_paginator($activities));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function client_list_public($id)
    {
        $activities = Activity::where('client_id', $id)->where('is_public', 1)->with(['about', 'user' => function($querytwo){
                $querytwo->select('id', 'name', 'last_name');
            }])->paginate(20);

        return response()->json(custom_paginator($activities));
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function client_store(Request $request)
    {
        $activity = Activity::create($request->all());

        return response()->json([
            'message' => 'Activity created.',
            'activity' => $activity
        ]);
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

        $activity = Activity::create($request->all());

        return response()->json([
            'message' => 'Activity created.',
            'activity' => $activity->fresh(['client', 'confirmed_by'])
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
        $activity = Activity::find($id);

        return response()->json(['data' => $activity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $activity = tap(Activity::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Activity updated.',
            'activity' => $activity
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
        $destroyed = Activity::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Activity destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Activity not found.',
        ], 404);

    }
}
