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

        $participation = EventParticipant::where('event_id', $request->get('event_id'))->where('participant_id', \Auth::user()->id)->destroy();

        return response()->json([
            'message' => 'Participation canceled.',
        ], 200);
    }


}
