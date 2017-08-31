<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
        $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->with('professional')->get();

        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->professional->makeHidden(['companies','categories','blank_password']);

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with('subscription')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client);

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $calendar_setting->setAttribute('schedules', $schedules);

        }
        
        return response()->json(['schedules' => $calendar_settings]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function professionalCalendar(Request $request)
    {
        $calendar_settings = ProfessionalCalendarSetting::where('professional_id', \Auth::user()->id)
            ->with('company')->get();

        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->makeHidden(['professional']);

            $schedules = Schedule::where('company_id', $calendar_setting->company_id)
                ->where('category_id', $calendar_setting->category_id)
                ->where('professional_id', $calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with('subscription')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client);

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $calendar_setting->setAttribute('schedules', $schedules);

        }

        return response()->json(['schedules' => $calendar_settings]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function clientCalendar(Request $request)
    {

        $client_schedules = Schedule::whereHas('subscription', function ($query) {
            $query->where('client_id', \Auth::user()->id);
        })->whereBetween('date', [$request->get('start'), $request->get('end')])
            ->with('category')
            ->get()
            ->groupBy('company_id');

            $companies = [];
            foreach($client_schedules as $key => $schedules)
            {
                foreach ($schedules as $schedule){
                    $schedule->professional->makeHidden(['companies','categories','blank_password', 'password', 'remember_token']);
                }
                $company = Company::find($key);

                $company->setAttribute('schedules', $schedules);

                $companies[] = $company;
            }

        return response()->json(['schedules' => $companies]);
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

        $schedule->professional->makeHidden(['companies', 'categories', 'blank_password', 'password', 'remember_token']);

        $schedule->makeHidden(['subscription']);

        return response()->json([
            'message' => 'Scheduled.',
            'schedule' => $schedule->load('category')
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
