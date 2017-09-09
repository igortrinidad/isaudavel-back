<?php

namespace App\Http\Controllers;

use App\Models\ClientSubscription;
use App\Models\ProfessionalCalendarSetting;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ProfessionalCalendarSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $professional_calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->with(['professional' => function($query){
                $query->select('id', 'name', 'last_name');
            }])->get();

        foreach ($professional_calendar_settings as $key => $calendar_setting){

            $new_workdays = [];

            foreach ($calendar_setting->workdays  as $key => $professional_workday) {
                $professional_workday['clients_count'] = 0;
                $professional_workday['is_available'] = true;

                $new_workdays[$key] = $professional_workday;
            }

            $calendar_setting->workdays = $new_workdays;

            $professional_workdays = $calendar_setting->workdays;

            $client_subs = ClientSubscription::where('company_id', $request->get('company_id'))
                ->whereHas('plan', function($query) use($request){
                    $query ->where('category_id', $request->get('category_id'));
                })->get();

            foreach ($client_subs as $client_sub){
                $client_workdays = $client_sub->workdays;

                foreach($client_workdays as $workday){
                    if($calendar_setting->professional_id == $workday['professional_id']){

                        foreach ($professional_workdays as  $key2 => $professional_workday) {

                            if ($professional_workday['dow'] == $workday['dow'] && $professional_workday['init'] == $workday['init']) {
                                $professional_workday['clients_count'] = $professional_workday['clients_count'] + 1;
                                $professional_workday['is_available'] = !($professional_workday['clients_count'] >= $professional_workday['quantity']);

                                $professional_workdays[$key2] = $professional_workday;
                            }

                        }
                    }
                }

            }
            $calendar_setting->workdays  = $professional_workdays;
        }

        return response()->json(['professional_calendar_settings' => $professional_calendar_settings]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function toReschedule(Request $request)
    {
        $professional_calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->with(['professional' => function($query){
                $query->select('id', 'name', 'last_name');
            }])->get();

        foreach($professional_calendar_settings as $professional_calendar_setting){
            $professional_calendar_setting->professional->makeHidden(['companies', 'categories', 'blank_password']);

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $professional_calendar_setting->professional->id)
                ->where('date', $request->get('date'))->orderBy('time')->get();

            $professional_calendar_setting->setAttribute('schedules', $schedules);
        }

        return response()->json(['professional_calendar_settings' => $professional_calendar_settings]);


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calendar_setting = ProfessionalCalendarSetting::create($request->all());

        return response()->json([
            'message' => 'Calendar setting created.',
            'calendar_setting' => $calendar_setting
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //First or create
        $calendar_setting = ProfessionalCalendarSetting::firstOrCreate(
            [
                'company_id' => $request->get('company_id'),
                'category_id' => $request->get('category_id'),
                'professional_id' => $request->get('professional_id')
            ], ['is_active' => false, 'workdays' => json_decode('[]')]);

        return response()->json(['calendar_setting' => $calendar_setting->fresh()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $calendar_setting = tap(ProfessionalCalendarSetting::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Calendar setting updated.',
            'calendar_setting' => $calendar_setting
        ]);
    }
}
