<?php

namespace App\Http\Controllers;

use App\Models\ClientSubscription;
use App\Models\ProfessionalCalendarSetting;
use App\Models\Schedule;
use App\Models\SingleSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

        $client_subscriptions = ClientSubscription::where('company_id', $request->get('company_id'))
            ->whereHas('plan', function ($query) use ($request) {
                $query->where('category_id', $request->get('category_id'));
            })
            ->whereBetween('expire_at', [Carbon::now()->format('Y-m-d'), $request->get('date')])
            ->where('is_active', true)
            ->where('auto_renew', true)
            ->get();

        foreach($professional_calendar_settings as $professional_calendar_setting){
            $professional_calendar_setting->professional->makeHidden(['companies', 'categories', 'blank_password']);

            $fake_schedules = new Collection();

            $date = Carbon::parse($request->get('date'));

            foreach ($client_subscriptions as $client_subscription) {

                $index = null;

                // get dow index
                foreach($client_subscription->workdays as $key => $workday) {

                    if($workday['dow'] == $date->dayOfWeek){
                        $index = $key;
                    }
                }

                $expire_current_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isCurrentMonth();
                $expire_next_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isNextMonth();

                if ($index > -1 && $client_subscription->workdays[$index]['dow'] == $date->dayOfWeek && $date->isFuture() && $client_subscription->workdays[$index]['professional_id'] == $professional_calendar_setting->professional_id) {

                    if(!$expire_next_month || $date->isNextMonth() && $expire_next_month || $expire_current_month && $date->isNextMonth() && !$expire_next_month ){
                        $schedule_data = [
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'client' => $client_subscription->client()->select('id', 'name', 'last_name')->first()->toArray(),
                            'company_id' => $client_subscription->company_id,
                            'date' => $date->format('d/m/Y'),
                            'time' => $client_subscription->workdays[$index]['init'].':00',
                            'professional_id' => $client_subscription->workdays[$index]['professional_id'],
                            'is_fake' => true
                        ];

                        $exists = Schedule::where([
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'company_id' => $client_subscription->company_id,
                            'date' => $date->format('Y-m-d'),
                            'time' => $client_subscription->workdays[$index]['init'].':00',
                            'professional_id' => $client_subscription->workdays[$index]['professional_id'],
                        ])->first();

                        if(!$exists){
                            $fake_schedules->push($schedule_data);
                        }
                    }

                }
            }

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $professional_calendar_setting->professional->id)
                ->where('date', $request->get('date'))->orderBy('time')->get();

            $single_schedules = SingleSchedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $professional_calendar_setting->professional_id)
                ->where('date', $request->get('date'))
                ->with( 'professional', 'client')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $professional_calendar_setting->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray(), $single_schedules->toArray()));
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
            ], ['is_active' => false, 'workdays' => json_decode('[]'), 'unavailable_dates_range' => json_decode('[]')]);

        return response()->json(['calendar_setting' => $calendar_setting->fresh()]);
    }

    /**
     * Lista as configuracoes de horarios para o dashboard do profissional
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list_professional_dashboard(Request $request)
    {

        $professional_calendar_settings = ProfessionalCalendarSetting::with(['company', 'category'])->where('professional_id', $request->get('professional_id'))->get();

        return response()->json(['professional_calendar_settings' => $professional_calendar_settings]);
    }

    /**
     * Lista de horários para o profissional
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show_professional_dashboard($id)
    {
        //First or create
        $professional_calendar_setting = ProfessionalCalendarSetting::with(['company', 'category'])->find($id);

        return response()->json(['professional_calendar_setting' => $professional_calendar_setting]);
    }

    /**
     * Lista as configuracoes de horarios para o dashboard do profissional na company
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function company_professional_workdays(Request $request)
    {

        $professional_calendar_settings = ProfessionalCalendarSetting::
            with(['category'])
            ->where('professional_id', $request->get('professional_id'))
            ->where('company_id', $request->get('company_id'))
            ->get();

        return response()->json(['professional_calendar_settings' => $professional_calendar_settings]);
    }

    /**
     * Em teste ainda
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function company_workdays_list(Request $request)
    {
        $workdays = ProfessionalCalendarSetting::
        where('company_id', $request->get('company_id'))
        ->where('category_id', $request->get('category_id'))
        ->where('professional_id', $request->get('professional_id'))
        ->first();

        return response()->json(['workdays' => $workdays->workdays]);
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
