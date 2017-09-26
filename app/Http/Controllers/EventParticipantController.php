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

        //Send Mail
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Novo participante</h4>';
        $data['messageOne'] = 'Seu evento '. $participation->event->name .' tem um novo participante:';
        $data['messageTwo'] = '<strong>Nome:</strong> '.$participation->participant->full_name;
        $data['messageThree'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Novo participante';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $participation){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($participation->event->from->email, $participation->event->from->full_name)->subject($data['messageSubject']);
        });

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

        $old_participation = EventParticipant::where('event_id', $request->get('event_id'))->where('participant_id', \Auth::user()->id)->first();
        $participation = EventParticipant::where('event_id', $request->get('event_id'))->where('participant_id', \Auth::user()->id)->delete();


        //Send Mail
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Cancelamento de participante</h4>';
        $data['messageOne'] = 'O participante '. $old_participation->participant->full_name . ' cancelou a participação no seu evento '. $old_participation->event->name .'.';
        $data['messageTwo'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Cancelamento de participante';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $old_participation){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($old_participation->event->from->email, $old_participation->event->from->full_name)->subject($data['messageSubject']);
        });


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
