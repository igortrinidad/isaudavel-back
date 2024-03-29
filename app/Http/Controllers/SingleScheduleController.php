<?php

namespace App\Http\Controllers;

use App\Events\ClientNotification;
use App\Events\CompanyNotification;
use App\Mail\DefaultEmail;
use App\Models\SingleSchedule;
use App\Models\CategoryCalendarSetting;
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


        //Notify the client
        event(new ClientNotification($single_schedule->client_id, ['type' => 'new_single_schedule', 'payload' => $single_schedule]));

        //Notify the professional
        event(new CompanyNotification($single_schedule->company_id, ['type' => 'new_single_schedule', 'payload' => $single_schedule]));

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Novo agendamento</h4>';
        $data['messageOne'] = 'O usuário <b>'. \Auth::user()->full_name . '</b> adicionou um novo agendamento de  <b>' .$single_schedule->category->name . '</b>  para você.<hr>
        <p><b>Horário</b></p>
        <b>' .$single_schedule->date . ' ' . $single_schedule->time . '</b>';

        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Novo agendamento';

        //Mail to client
        \Mail::to($single_schedule->client->email, $single_schedule->client->full_name)->queue(new DefaultEmail($data, ['new-single_schedule-client']));

        //Mail to professional
        if($single_schedule->professional_id){
            \Mail::to($single_schedule->professional->email, $single_schedule->professional->full_name)->queue(new DefaultEmail($data, ['new-single_schedule-professional']));
        }

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

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $single_schedule->company_id)
            ->where('category_id', $single_schedule->category_id)
            ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
            ->first();

        $single_schedule->setAttribute('category_calendar_settings', $category_calendar_settings);

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

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $single_schedule->company_id)
            ->where('category_id', $single_schedule->category_id)
            ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
            ->first();

        $single_schedule->setAttribute('category_calendar_settings', $category_calendar_settings);


        //Notify the client
        if(\Auth::user()->role == 'professional'){
            event(new ClientNotification($single_schedule->client_id, ['type' => 'single_reschedule', 'payload' => ['single_schedule' => $single_schedule, 'old_single_schedule' => $old_schedule]]));

            event(new CompanyNotification($single_schedule->company_id, ['type' => 'single_reschedule_by_professional', 'payload' => ['single_schedule' => $single_schedule, 'old_single_schedule' => $old_schedule]]));
        }

        //Notify the company
        if(\Auth::user()->role == 'client'){
            event(new CompanyNotification($single_schedule->company_id, ['type' => 'single_reschedule', 'payload' => ['single_schedule' => $single_schedule, 'old_single_schedule' => $old_schedule]]));
        }

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Alteração de horário</h4>';
        $data['messageOne'] = 'O usuário <b>'. \Auth::user()->full_name . '</b> acabou de alterar seu horário de  <b>' .$single_schedule->category->name . '</b> marcado anteriormente para ' . $old_schedule->date . ' ' . $old_schedule->time . '.<hr>
        <p><b>Novo horário</b></p>
        <b>' .$single_schedule->date . ' ' . $single_schedule->time . '</b>';

        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Alteração de horário';

        //Mail to client
        \Mail::to($single_schedule->client->email, $single_schedule->client->full_name)->queue(new DefaultEmail($data, ['client-single_reschedule']));

        //Mail to professional
        if($single_schedule->professional_id){
            \Mail::to($single_schedule->professional->email, $single_schedule->professional->full_name)->queue(new DefaultEmail($data, ['professional-single_reschedule']));
        }

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

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $single_schedule->company_id)
            ->where('category_id', $single_schedule->category_id)
            ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
            ->first();

        $single_schedule->setAttribute('category_calendar_settings', $category_calendar_settings);

        //Notify the client
        if(\Auth::user()->role == 'professional'){
            event(new ClientNotification($single_schedule->client->id, ['type' => 'cancel_single_schedule', 'payload' => $single_schedule]));

            event(new CompanyNotification($single_schedule->company_id, ['type' => 'cancel_single_schedule_by_professional', 'payload' => $single_schedule]));
        }

        //Notify the company
        if(\Auth::user()->role == 'client'){
            event(new CompanyNotification($single_schedule->company_id, ['type' => 'cancel_single_schedule', 'payload' => $single_schedule]));
        }

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Cancelamento de horário</h4>';
        $data['messageOne'] = 'O horário de ' .$single_schedule->category->name .  ' marcado para <strong>' .$old_schedule->date . ' ' . $old_schedule->time . ' </strong> foi cancelado por '. \Auth::user()->full_name.' às '. Carbon::now()->format('d/m/Y H:i:s') .'.';

        $data['messageSubject'] = 'Cancelamento de horário';

        //Mail to client
        \Mail::to($single_schedule->client->email, $single_schedule->client->full_name)->queue(new DefaultEmail($data, ['client-single_schedule-cancel']));

        //Mail to professional
        if($single_schedule->professional_id){
            \Mail::to($single_schedule->professional->email, $single_schedule->professional->full_name)->queue(new DefaultEmail($data, ['professional-single_schedule-cancel']));
        }

        return response()->json([
            'message' => 'Canceled.',
            'single_schedule' => $single_schedule
        ]);
    }
}
