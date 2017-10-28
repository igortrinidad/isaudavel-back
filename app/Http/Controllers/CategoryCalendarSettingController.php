<?php

namespace App\Http\Controllers;

use App\Models\CategoryCalendarSetting;
use App\Models\ClientSubscription;
use App\Models\ProfessionalCalendarSetting;
use App\Models\Schedule;
use App\Models\SingleSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryCalendarSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->with('category')->first();

        $new_workdays = [];

        foreach ($category_calendar_settings->workdays  as $key => $category_workday) {
            $category_workday['clients_count'] = 0;
            $category_workday['is_available'] = true;

            $new_workdays[$key] = $category_workday;
        }

        $category_calendar_settings->workdays = $new_workdays;

        $category_workdays = $category_calendar_settings->workdays;

        $client_subs = ClientSubscription::where('company_id', $request->get('company_id'))
            ->whereHas('plan', function($query) use($request){
                $query ->where('category_id', $request->get('category_id'));
            })->get();

        foreach ($client_subs as $client_sub){
            $client_workdays = $client_sub->workdays;

            foreach($client_workdays as $workday){
                foreach ($category_workdays as  $key2 => $category_workday) {

                    if ($category_workday['dow'] == $workday['dow'] && $category_workday['init'] == $workday['init']) {
                        $category_workday['clients_count'] = $category_workday['clients_count'] + 1;
                        $category_workday['is_available'] = !($category_workday['clients_count'] >= $category_workday['quantity']);

                        $category_workdays[$key2] = $category_workday;
                    }

                }
            }

        }
        $category_calendar_settings->workdays  = $category_workdays;

        return response()->json(['category_calendar_settings' => $category_calendar_settings]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function toReschedule(Request $request)
    {
        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->with('category')
            ->with(['category' => function ($query) {
                $query->select('id', 'name');
            }])->first();

        $client_subscriptions = ClientSubscription::where('company_id', $request->get('company_id'))
            ->whereHas('plan', function ($query) use ($request) {
                $query->where('category_id', $request->get('category_id'));
            })
            ->whereBetween('expire_at', [Carbon::now()->format('Y-m-d'), $request->get('date')])
            ->where('is_active', true)
            ->where('auto_renew', true)
            ->get();

        $fake_schedules = new Collection();

        $date = Carbon::parse($request->get('date'));

        foreach ($client_subscriptions as $client_subscription) {

            $index = null;

            // get dow index
            foreach ($client_subscription->workdays as $key => $workday) {

                if ($workday['dow'] == $date->dayOfWeek) {
                    $index = $key;
                }
            }

            $expire_current_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isCurrentMonth();
            $expire_next_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isNextMonth();

            if ($index > -1 && $client_subscription->workdays[$index]['dow'] == $date->dayOfWeek && $date->isFuture()) {

                if (!$expire_next_month || $date->isNextMonth() && $expire_next_month || $expire_current_month && $date->isNextMonth() && !$expire_next_month) {

                    $schedule_data = [
                        'subscription_id' => $client_subscription->id,
                        'category_id' => $client_subscription->plan->category_id,
                        'client' => $client_subscription->client()->select('id', 'name', 'last_name')->first()->toArray(),
                        'company_id' => $client_subscription->company_id,
                        'date' => $date->format('d/m/Y'),
                        'time' => $client_subscription->workdays[$index]['init'] . ':00',
                        'professional_id' => $client_subscription->workdays[$index]['professional_id'],
                        'is_fake' => true
                    ];

                    $exists = Schedule::where([
                        'subscription_id' => $client_subscription->id,
                        'category_id' => $client_subscription->plan->category_id,
                        'company_id' => $client_subscription->company_id,
                        'date' => $date->format('Y-m-d'),
                        'time' => $client_subscription->workdays[$index]['init'] . ':00',
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
            ->where('date', $request->get('date'))->orderBy('time')->get();

        $single_schedules = SingleSchedule::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->where('date', $request->get('date'))
            ->with('professional', 'client')
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $category_calendar_settings->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray(), $single_schedules->toArray()));

        return response()->json(['category_calendar_settings' => $category_calendar_settings]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calendar_setting = CategoryCalendarSetting::create($request->all());

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
        $calendar_setting = CategoryCalendarSetting::firstOrCreate(
            [
                'company_id' => $request->get('company_id'),
                'category_id' => $request->get('category_id')
            ], ['is_professional_scheduled' => false, 'workdays' => json_decode('[]')]);

        if($calendar_setting->is_professional_scheduled){
            $professionals_calendar_settings = ProfessionalCalendarSetting::where( 'company_id',$request->get('company_id'))
                ->where( 'category_id', $request->get('category_id'))
                ->select('id', 'professional_id', 'is_active')
                ->get();

            $calendar_setting->setAttribute('professionals_calendar_settings', $professionals_calendar_settings);

            return response()->json(['calendar_setting' => $calendar_setting]);
        }

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
        $calendar_setting = CategoryCalendarSetting::find($request->get('id'))->update($request->all());

        //Chama o método show novamente para não precisar repetir o processo...
        return $this->show($request);
    }
}
