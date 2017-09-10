<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\Request;

class EventParticipantController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $comments = EventParticipant::where('event_id', $id)->with('participant')->orderBy('created_at', 'desc')->paginate(50);

        return response()->json(custom_paginator($comments));
    }
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $request->merge([
            'participant_id' => \Auth::user()->id,
            'participant_type' => get_class(\Auth::user())
        ]);

        $participation = EventParticipant::create($request->all());

        return response()->json([
            'message' => 'Participation confirmed.',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {

        $participation = EventParticipant::where('event_id', $request->get('event_id'))->where('participant_id', \Auth::user()->id)->delete();

        return response()->json([
            'message' => 'Participation canceled.',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check_presence(Request $request)
    {

        $participation = EventParticipant::where('event_id', $request->get('event_id'))->where('participant_id', \Auth::user()->id)->first();

        return response()->json([
            'presence' => ($participation) ? true : false,
        ], 200);
    }


}
