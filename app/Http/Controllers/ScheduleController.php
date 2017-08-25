<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalCalendarSetting;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $schedules = Schedule::where('company_id', $id)->with(['client', 'plan'])->get();

        return response()->json(['schedules' => $schedules]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function forCalendar(Request $request)
    {
        $schedules = Schedule::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->whereBetween('date', [$request->get('start'), $request->get('end')])
            ->with(['professional' => function($query){
                $query->select('id', 'name', 'last_name', 'email');
            }, 'subscription'])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        foreach($schedules as $schedule){

            $schedule->professional->setHidden(['companies','categories','blank_password']);

            $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->first();

            $schedule->setAttribute('client', $schedule->subscription->client);
            $schedule->setAttribute('professional_workdays', $calendar_settings->workdays);

            $schedule->setHidden(['subscription']);
        }

        return response()->json(['schedules' => $schedules]);
    }



    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);

        return response()->json(['schedule' => $schedule]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $schedule = Schedule::create($request->all());

        return response()->json([
            'message' => 'Schedule created.',
            'schedule' => $schedule->fresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Schedule updated.',
            'schedule' => $schedule
        ]);
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

        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $schedule->setAttribute('client', $schedule->subscription->client);

        $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->first();

        $schedule->setAttribute('professional_workdays', $calendar_settings->workdays);

        $schedule->professional->setHidden(['companies', 'categories', 'blank_password', 'password', 'remember_token']);

        $schedule->setHidden(['subscription']);

        return response()->json([
            'message' => 'Scheduled.',
            'schedule' => $schedule
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
        $destroyed = Schedule::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Schedule destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Schedule not found.',
        ], 404);

    }

}
