<?php

namespace App\Http\Controllers;

use App\Models\SingleSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SingleScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $single_schedules = SingleSchedule::where('company_id', $request->get('company_id'))
            ->where('client_id', $request->get('client_id'))
            ->with( 'category', 'professional', 'client')
            ->whereBetween('date', [$request->get('init'), $request->get('end')])
            ->orderBy('date', 'desc')
            ->orderBy('time', 'asc')
            ->paginate(10);

        return response()->json(custom_paginator($single_schedules, 'single_schedules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $single_schedule = tap(SingleSchedule::create($request->all()))->load('company', 'client', 'category', 'professional');

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Novo agendamento</h4>';
        $data['messageOne'] = 'O usuário <b>'. \Auth::user()->full_name . '</b> adicionou um novo agendamento de  <b>' .$single_schedule->category->name . '</b>  para você.<hr>
        <p><b>Horário</b></p>
        <b>' .$single_schedule->date . ' ' . $single_schedule->time . '</b>';

        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Novo agendamento';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $single_schedule){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($single_schedule->client->email, $single_schedule->client->full_name)->subject($data['messageSubject']);

            if($single_schedule->professional_id){
                $message->cc($single_schedule->professional->email, $single_schedule->professional->full_name)->subject($data['messageSubject']);
            }

        });

        return response()->json([
            'message' => 'Single schedule created.',
            'single_schedule' => $single_schedule
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
        $single_schedule = SingleSchedule::with('company', 'client', 'category', 'professional')->find($id);

        return response()->json(['single_schedule' => $single_schedule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $single_schedule = tap(SingleSchedule::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Single schedule updated.',
            'single_schedule' => $single_schedule
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
        $destroyed = SingleSchedule::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Single schedule destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Single schedule not found.',
        ], 404);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reschedule(Request $request)
    {
        $request->merge(['is_rescheduled' => true, 'reschedule_by' => \Auth::user()->full_name, 'reschedule_at' => Carbon::now()]);

        $old_schedule = SingleSchedule::find($request->get('id'));

        $single_schedule = tap(SingleSchedule::find($request->get('id')))->update($request->all())->fresh()->load('company', 'client', 'category', 'professional');

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Alteração de horário</h4>';
        $data['messageOne'] = 'O usuário <b>'. \Auth::user()->full_name . '</b> acabou de alterar seu horário de  <b>' .$single_schedule->category->name . '</b> marcado anteriormente para ' . $old_schedule->date . ' ' . $old_schedule->time . '.<hr>
        <p><b>Novo horário</b></p>
        <b>' .$single_schedule->date . ' ' . $single_schedule->time . '</b>';

        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Alteração de horário';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $single_schedule){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($single_schedule->client->email, $single_schedule->client->full_name)->subject($data['messageSubject']);
            
            if($single_schedule->professional_id){
                $message->cc($single_schedule->professional->email, $single_schedule->professional->full_name)->subject($data['messageSubject']);
            }
        });

        return response()->json([
            'message' => 'Rescheduled.',
            'single_schedule' => $single_schedule
        ]);
    }

    /**
     * confirm the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $request->merge(['is_confirmed' => true, 'confirmed_by' => \Auth::user()->full_name, 'confirmed_at' => Carbon::now()]);

        $single_schedule = tap(SingleSchedule::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Confirmed.',
            'single_schedule' => $single_schedule->load('company', 'client', 'category', 'professional')
        ]);
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        $request->merge(['is_canceled' => true, 'canceled_by' => \Auth::user()->full_name, 'canceled_at' => Carbon::now()]);

        $old_schedule = SingleSchedule::find($request->get('id'));
        $single_schedule = tap(SingleSchedule::find($request->get('id')))->update($request->all())->fresh()->load('company', 'client', 'category', 'professional');

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Cancelamento de horário</h4>';
        $data['messageOne'] = 'O horário de ' .$single_schedule->category->name .  ' marcado para <strong>' .$old_schedule->date . ' ' . $old_schedule->time . ' </strong> foi cancelado por '. \Auth::user()->full_name.' às '. Carbon::now()->format('d/m/Y H:i:s') .'.';

        $data['messageSubject'] = 'Cancelamento de horário';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $single_schedule){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($single_schedule->client->email, $single_schedule->client->full_name)->subject($data['messageSubject']);
            $message->cc($single_schedule->professional->email, $single_schedule->professional->full_name)->subject($data['messageSubject']);
        });

        return response()->json([
            'message' => 'Canceled.',
            'single_schedule' => $single_schedule
        ]);
    }
}
