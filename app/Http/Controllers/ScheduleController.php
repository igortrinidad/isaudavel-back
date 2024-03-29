<?php

namespace App\Http\Controllers;

use App\Events\ClientNotification;
use App\Events\CompanyNotification;
use App\Mail\DefaultEmail;
use App\Models\CategoryCalendarSetting;
use App\Models\ClientSubscription;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\ProfessionalCalendarSetting;
use App\Models\Professional;
use App\Models\Schedule;
use App\Models\SingleSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )->first();

        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->professional->makeHidden(['companies','categories','blank_password']);

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with(['subscription', 'professional'])
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

        return response()->json(['schedules' => $calendar_settings, 'category_calendar_settings'=>  $category_calendar_settings]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function forCalendarNew(Request $request)
    {

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->select('id', 'category_id', 'advance_schedule', 'advance_reschedule', 'cancel_schedule', 'is_professional_scheduled', 'workdays')
            ->with(['category' => function ($query) {
                $query->select('id', 'name');
            }])
            ->first();

        /*
         * Scheduled by professional
         */
        if($category_calendar_settings->is_professional_scheduled){
            $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->with('professional')->get();

            $client_subscriptions = ClientSubscription::where('company_id', $request->get('company_id'))
                ->whereHas('plan', function ($query) use ($request) {
                    $query->where('category_id', $request->get('category_id'));
                })
                ->whereBetween('expire_at', [Carbon::now()->format('Y-m-d'), $request->get('end')])
                ->where('is_active', true)
                ->where('auto_renew', true)
                ->get();

            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'));

            $date_range = [];

            while ($start->lte($end)) {

                $date_range[] = $start->copy();

                $start->addDay();
            }

            foreach($calendar_settings as $calendar_setting){

                $calendar_setting->professional->makeHidden(['companies','categories','blank_password']);

                $schedules = Schedule::where('company_id', $request->get('company_id'))
                    ->where('category_id', $request->get('category_id'))
                    ->where('professional_id', $calendar_setting->professional_id)
                    ->whereBetween('date', [$request->get('start'), $request->get('end')])
                    ->with(['subscription', 'professional'])
                    ->orderBy('date')
                    ->orderBy('time')
                    ->get();

                $single_schedules = SingleSchedule::where('company_id', $request->get('company_id'))
                    ->where('category_id', $request->get('category_id'))
                    ->where('professional_id', $calendar_setting->professional_id)
                    ->whereBetween('date', [$request->get('start'), $request->get('end')])
                    ->with( 'professional', 'client')
                    ->orderBy('date')
                    ->orderBy('time')
                    ->get();

                $fake_schedules = new Collection();

                foreach ($client_subscriptions as $client_subscription) {

                    foreach($date_range as $date){

                        if(empty($client_subscription->workdays)){
                            continue;
                        }

                        // get dow index
                        $dow_index = null;
                        foreach($client_subscription->workdays as $key => $workday) {

                            if($workday['dow'] == $date->dayOfWeek){
                                $dow_index = $key;
                            }
                        }

                        $expire_current_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isCurrentMonth();
                        $expire_next_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isNextMonth();

                        if ($dow_index > -1 && $client_subscription->workdays[$dow_index]['dow'] == $date->dayOfWeek && $date->isFuture() &&  $client_subscription->workdays[$dow_index]['professional_id'] == $calendar_setting->professional_id) {

                            if(!$expire_next_month || $date->isNextMonth() && $expire_next_month || $expire_current_month && $date->isNextMonth() && !$expire_next_month ){

                                $schedule_data = [
                                    'subscription_id' => $client_subscription->id,
                                    'category_id' => $client_subscription->plan->category_id,
                                    'client' => $client_subscription->client()->select('id', 'name', 'last_name')->first()->toArray(),
                                    'company_id' => $client_subscription->company_id,
                                    'date' => $date->format('d/m/Y'),
                                    'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                                    'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                                    'is_fake' => true
                                ];

                                $exists = Schedule::where([
                                    'subscription_id' => $client_subscription->id,
                                    'category_id' => $client_subscription->plan->category_id,
                                    'company_id' => $client_subscription->company_id,
                                    'date' => $date->format('Y-m-d'),
                                    'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                                    'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                                ])->with(['professional'])->first();

                                if(!$exists){
                                    $fake_schedules->push($schedule_data);
                                }
                            }

                        }
                    }
                }

                foreach($schedules as $schedule){

                    $schedule->professional->makeHidden(['companies','categories','blank_password']);

                    $schedule->setAttribute('client', $schedule->subscription->client()->select('id', 'name', 'last_name')->first());

                    $schedule->makeHidden(['subscription', 'professional']);
                }

                foreach($single_schedules as $single_schedule){
                    $single_schedule->setAttribute('is_fake', false);
                }


                $calendar_setting->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray(), $single_schedules->toArray()));
                $calendar_setting->setAttribute('is_professional_scheduled', true);
            }

            return response()->json(['schedules' => $calendar_settings, 'category_calendar_settings'=>  $category_calendar_settings]);
        }


        /*
         * Scheduled by category
         */
        if(!$category_calendar_settings->is_professional_scheduled){

            $client_subscriptions = ClientSubscription::where('company_id', $request->get('company_id'))
                ->whereHas('plan', function ($query) use ($request) {
                    $query->where('category_id', $request->get('category_id'));
                })
                ->whereBetween('expire_at', [Carbon::now()->format('Y-m-d'), $request->get('end')])
                ->where('is_active', true)
                ->where('auto_renew', true)
                ->get();

            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'));

            $date_range = [];

            while ($start->lte($end)) {

                $date_range[] = $start->copy();

                $start->addDay();
            }

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with(['subscription', 'professional'])
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $single_schedules = SingleSchedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with( 'professional', 'client')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $fake_schedules = new Collection();

            foreach ($client_subscriptions as $client_subscription) {

                foreach($date_range as $date){

                    if(empty($client_subscription->workdays)){
                        continue;
                    }

                    // get dow index
                    $dow_index = null;
                    foreach($client_subscription->workdays as $key => $workday) {

                        if($workday['dow'] == $date->dayOfWeek){
                            $dow_index = $key;
                        }
                    }

                    $expire_current_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isCurrentMonth();
                    $expire_next_month = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at)->isNextMonth();

                    if ($dow_index > -1 && $client_subscription->workdays[$dow_index]['dow'] == $date->dayOfWeek && $date->isFuture()) {

                        if(!$expire_next_month || $date->isNextMonth() && $expire_next_month || $expire_current_month && $date->isNextMonth() && !$expire_next_month ){
                            $schedule_data = [
                                'subscription_id' => $client_subscription->id,
                                'category_id' => $client_subscription->plan->category_id,
                                'client' => $client_subscription->client()->select('id', 'name', 'last_name')->first()->toArray(),
                                'company_id' => $client_subscription->company_id,
                                'date' => $date->format('d/m/Y'),
                                'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                                'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                                'is_fake' => true
                            ];

                            $exists = Schedule::where([
                                'subscription_id' => $client_subscription->id,
                                'category_id' => $client_subscription->plan->category_id,
                                'company_id' => $client_subscription->company_id,
                                'date' => $date->format('Y-m-d'),
                                'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                                'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                            ])->with(['professional'])->first();

                            if(!$exists){
                                $fake_schedules->push($schedule_data);
                            }
                        }
                    }
                }
            }

            foreach($schedules as $schedule){

                $schedule->setAttribute('client', $schedule->subscription->client()->select('id', 'name', 'last_name')->first());

                $schedule->makeHidden(['subscription', 'professional']);
            }

            foreach($single_schedules as $single_schedule){
                $single_schedule->setAttribute('is_fake', false);
            }


            $category_calendar_settings->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray(), $single_schedules->toArray()));

            $schedules = [];
            $schedules[] = $category_calendar_settings;

            return response()->json(['schedules' => $schedules]);
        }
    }

    /**
     * Display a listing of schedules for calendar by day.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function schedulesByDay(Request $request)
    {
        $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->with('professional')->get();

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )->first();

        $client_subscriptions = ClientSubscription::where('company_id', $request->get('company_id'))
            ->whereHas('plan', function ($query) use ($request) {
                $query->where('category_id', $request->get('category_id'));
            })
            ->where('expire_at', '<', $request->get('date'))
            ->where('is_active', true)
            ->where('auto_renew', true)
            ->get();


        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->professional->makeHidden(['companies','categories','blank_password']);

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $calendar_setting->professional_id)
                ->where('date', $request->get('date'))
                ->with(['subscription', 'professional'])
                ->orderBy('date')
                ->orderBy('time')
                ->get();

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
                if ($index > -1 && $client_subscription->workdays[$index]['dow'] == $date->dayOfWeek && $date->isFuture() && $client_subscription->workdays[$index]['professional_id'] == $calendar_setting->professional_id) {

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

                    $fake_schedules->push($schedule_data);

                }
            }

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client()->select('id', 'name', 'last_name')->first());

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $calendar_setting->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray()));
        }

        return response()->json(['schedules' => $calendar_settings, 'category_calendar_settings'=>  $category_calendar_settings]);
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
            ->with('company', 'category')->get();

        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->makeHidden(['professional']);

            $schedules = Schedule::where('company_id', $calendar_setting->company_id)
                ->where('category_id', $calendar_setting->category_id)
                ->where('professional_id', $calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with(['subscription', 'professional'])
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client);

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $calendar_setting->setAttribute('schedules', $schedules);
            $calendar_setting->setAttribute('category', $calendar_setting->category);

        }

        return response()->json(['schedules' => $calendar_settings]);
    }


    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function professionalCalendarNew(Request $request)
    {
        $professional = \Auth::user();

        $professional_calendar_settings = ProfessionalCalendarSetting::where('professional_id', $professional->id)
            ->with('company', 'category')->get();

        $categories_calendar_settings = CategoryCalendarSetting::whereHas('company', function($query) use ($professional){
            $query->whereIn('company_id', $professional->companies)->where('is_professional_scheduled', true);
        })->with('company', 'category')->get();

        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        $date_range = [];

        while ($start->lte($end)) {

            $date_range[] = $start->copy();

            $start->addDay();
        }

        // Professional scheduled
        foreach($professional_calendar_settings as $professional_calendar_setting){

            $professional_calendar_setting->makeHidden(['professional']);

            $schedules = Schedule::where('company_id', $professional_calendar_setting->company_id)
                ->where('category_id', $professional_calendar_setting->category_id)
                ->where('professional_id', $professional_calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with(['subscription', 'professional'])
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $single_schedules = SingleSchedule::where('company_id', $professional_calendar_setting->company_id)
                ->where('category_id', $professional_calendar_setting->category_id)
                ->where('professional_id', $professional_calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with( 'professional', 'client')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $client_subscriptions = ClientSubscription::where('company_id', $professional_calendar_setting->company_id)
                ->whereHas('plan', function ($query) use ($professional_calendar_setting) {
                    $query->where('category_id', $professional_calendar_setting->category_id);
                })
                ->whereBetween('expire_at', [Carbon::now()->format('Y-m-d'), $request->get('end')])
                ->where('is_active', true)
                ->where('auto_renew', true)
                ->get();

            $fake_schedules = new Collection();
            foreach ($client_subscriptions as $client_subscription) {

                foreach($date_range as $date){

                    if(empty($client_subscription->workdays)){
                        continue;
                    }

                    // get dow index
                    $dow_index = null;
                    foreach($client_subscription->workdays as $key => $workday) {

                        if($workday['dow'] == $date->dayOfWeek){
                            $dow_index = $key;
                        }
                    }

                    if ($dow_index > -1 && $client_subscription->workdays[$dow_index]['dow'] == $date->dayOfWeek && $date->isFuture() && $client_subscription->workdays[$dow_index]['professional_id'] == $professional_calendar_setting->professional_id) {

                        $schedule_data = [
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'client' => $client_subscription->client()->select('id', 'name', 'last_name')->first()->toArray(),
                            'company_id' => $client_subscription->company_id,
                            'date' => $date->format('d/m/Y'),
                            'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                            'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                            'is_fake' => true
                        ];

                        $exists = Schedule::where([
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'company_id' => $client_subscription->company_id,
                            'date' => $date->format('Y-m-d'),
                            'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                            'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                        ])->with(['professional'])->first();

                        if(!$exists){
                            $fake_schedules->push($schedule_data);
                        }

                    }
                }
            }

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client()->select('id', 'name', 'last_name')->first());

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $professional_calendar_setting->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray(), $single_schedules->toArray()));
        }


        //Category scheduled
        foreach($categories_calendar_settings as $category_calendar_setting){

            $schedules = Schedule::where('company_id', $category_calendar_setting->company_id)
                ->where('category_id', $category_calendar_setting->category_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with(['subscription', 'professional'])
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $single_schedules = SingleSchedule::where('company_id', $category_calendar_setting->company_id)
                ->where('category_id', $category_calendar_setting->category_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with( 'professional', 'client')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            $client_subscriptions = ClientSubscription::where('company_id', $category_calendar_setting->company_id)
                ->whereHas('plan', function ($query) use ($category_calendar_setting) {
                    $query->where('category_id', $category_calendar_setting->category_id);
                })
                ->whereBetween('expire_at', [Carbon::now()->format('Y-m-d'), $request->get('end')])
                ->where('is_active', true)
                ->where('auto_renew', true)
                ->get();

            $fake_schedules = new Collection();
            foreach ($client_subscriptions as $client_subscription) {

                foreach($date_range as $date){

                    if(empty($client_subscription->workdays)){
                        continue;
                    }

                    // get dow index
                    $dow_index = null;
                    foreach($client_subscription->workdays as $key => $workday) {

                        if($workday['dow'] == $date->dayOfWeek){
                            $dow_index = $key;
                        }
                    }

                    if ($dow_index > -1 && $client_subscription->workdays[$dow_index]['dow'] == $date->dayOfWeek && $date->isFuture()) {

                        $schedule_data = [
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'client' => $client_subscription->client()->select('id', 'name', 'last_name')->first()->toArray(),
                            'company_id' => $client_subscription->company_id,
                            'date' => $date->format('d/m/Y'),
                            'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                            'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                            'is_fake' => true
                        ];

                        $exists = Schedule::where([
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'company_id' => $client_subscription->company_id,
                            'date' => $date->format('Y-m-d'),
                            'time' => $client_subscription->workdays[$dow_index]['init'].':00',
                            'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                        ])->with(['professional'])->first();

                        if(!$exists){
                            $fake_schedules->push($schedule_data);
                        }

                    }
                }
            }

            foreach($schedules as $schedule){

                //$schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client()->select('id', 'name', 'last_name')->first());

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $category_calendar_setting->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $fake_schedules->toArray(), $single_schedules->toArray()));

        }



        return response()->json(['schedules' => $professional_calendar_settings->merge($categories_calendar_settings)]);

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
            ->with(['category', 'subscription' => function($query){
                $query->select('id', 'start_at', 'expire_at');
            }])
            ->get()
            ->groupBy('company_id');

            $companies = [];
            foreach($client_schedules as $key => $schedules)
            {

                foreach ($schedules as $schedule){
                   if($schedule->professional){
                       $schedule->professional->makeHidden(['companies','categories','blank_password', 'password', 'remember_token']);
                   }

                    $category_calendar_settings = CategoryCalendarSetting::where('company_id', $key)
                        ->where('category_id', $schedule->category_id)
                        ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
                        ->first();
                    $schedule->setAttribute('category_calendar_settings', $category_calendar_settings);
                }

                $singleSchedules = SingleSchedule::whereBetween('date', [$request->get('start'), $request->get('end')])
                    ->where('company_id', $key)
                    ->where('client_id', \Auth::user()->id)
                    ->with(['company', 'professional', 'client', 'category'])
                    ->get();

                $company = Company::find($key);

                $company->setAttribute('schedules', array_merge_recursive($schedules->toArray(), $singleSchedules->toArray()));

                $companies[] = $company;
            }

        return response()->json(['schedules' => $companies]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function clientSchedules(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;

        $client_schedules = Schedule::where('company_id', $request->get('company_id'))
            ->whereHas('subscription', function ($query) use($request) {
            $query->where('client_id', $request->get('client_id'));
        })->whereBetween('date', [$request->get('init'), $request->get('end')])
            ->with(['category', 'professional'])
            ->get();

        $single_schedules = SingleSchedule::whereBetween('date', [$request->get('init'), $request->get('end')])
            ->where('company_id', $request->get('company_id'))
            ->where('client_id', $request->get('client_id'))
            ->with(['professional', 'category'])
            ->get();


        $new_collection = collect(array_merge($client_schedules->toArray(), $single_schedules->toArray()));

        $new_collection =  $new_collection->sortByDesc(function($schedule)
        {
            return Carbon::createFromFormat('d/m/Y H:i:s',$schedule['date'].' '.$schedule['time'])->getTimestamp();
        });

        $paged = array_slice($new_collection->toArray(),($currentPage - 1) * $perPage, $perPage);

        $schedules = new LengthAwarePaginator($paged, count($new_collection), $perPage, $currentPage);

        return response()->json(custom_paginator($schedules, 'schedules'));
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $schedule = Schedule::with(['professional', 'company', 'category', 'subscription' => function($query){
            $query->select('id','client_id', 'plan_id', 'start_at', 'expire_at');
        }, 'subscription.client', 'subscription.plan', 'professional', 'company' ])->find($id);

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $schedule->company_id)
            ->where('category_id', $schedule->category_id)
            ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
            ->first();

        $schedule->setAttribute('category_calendar_settings', $category_calendar_settings);


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
            'schedule' => $schedule->load('category', 'professional')
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

        $old_schedule = Schedule::find($request->get('id'));
        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $schedule->company_id)
            ->where('category_id', $schedule->category_id)
            ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
            ->first();

        $schedule->setAttribute('client', $schedule->subscription->client);

        $schedule->setAttribute('category_calendar_settings', $category_calendar_settings);

        if($schedule->professional_id){
            $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->first();

            $schedule->setAttribute('professional_workdays', $calendar_settings->workdays);

            $schedule->professional->makeHidden(['companies', 'categories', 'blank_password', 'password', 'remember_token']);
        }

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Alteração de horário</h4>';
        $data['messageOne'] = 'O usuário <b>'. \Auth::user()->full_name . '</b> acabou de alterar seu horário de  <b>' .$schedule->category->name . '</b> marcado anteriormente para ' . $old_schedule->date . ' ' . $old_schedule->time . '.<hr>
        <p><b>Novo horário</b></p>
        <b>' .$schedule->date . ' ' . $schedule->time . '</b>';
        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';
        $data['messageSubject'] = 'Alteração de horário';

        //Notify the client
        if(\Auth::user()->role == 'professional'){

            event(new ClientNotification($schedule->client->id, ['type' => 'reschedule', 'payload' => ['schedule' => $schedule, 'old_schedule' => $old_schedule]]));

            \Mail::to($schedule->client->email, $schedule->client->full_name)->queue(new DefaultEmail($data, ['client-reschedule']));

            event(new CompanyNotification($schedule->company_id, ['type' => 'reschedule_by_professional', 'payload' => ['schedule' => $schedule, 'old_schedule' => $old_schedule]]));
        }

        //Notify the company
        if(\Auth::user()->role == 'client'){

            event(new CompanyNotification($schedule->company_id, ['type' => 'reschedule', 'payload' => ['schedule' => $schedule, 'old_schedule' => $old_schedule]]));

            \Mail::to($schedule->professional->email, $schedule->professional->full_name)->queue(new DefaultEmail($data, ['professional-reschedule']));

        }

        return response()->json([
            'message' => 'Rescheduled.',
            'schedule' => $schedule->load(['company', 'category', 'subscription' => function($query){
                $query->select('id','client_id', 'plan_id', 'start_at', 'expire_at');
            }, 'subscription.client', 'subscription.plan', 'professional', 'company' ])
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



    /**
     * confirm the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $request->merge(['is_confirmed' => true, 'confirmed_by' => \Auth::user()->full_name, 'confirmed_at' => Carbon::now()]);

        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $schedule->setAttribute('client', $schedule->subscription->client);

        return response()->json([
            'message' => 'Confirmed.',
            'schedule' => $schedule->load(['company', 'category', 'subscription' => function($query){
                $query->select('id','client_id', 'plan_id', 'start_at', 'expire_at');
            }, 'subscription.client', 'subscription.plan', 'professional', 'company' ])
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

        $old_schedule = Schedule::find($request->get('id'));
        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $schedule->company_id)
            ->where('category_id', $schedule->category_id)
            ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
            ->first();

        $schedule->setAttribute('client', $schedule->subscription->client);

        $schedule->setAttribute('category_calendar_settings', $category_calendar_settings);

        if($schedule->professional_id){
            $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->first();

            $schedule->setAttribute('professional_workdays', $calendar_settings->workdays);

            $schedule->professional->makeHidden(['companies', 'categories', 'blank_password', 'password', 'remember_token']);
        }

        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Cancelamento de horário</h4>';
        $data['messageOne'] = 'O horário de ' .$schedule->category->name .  ' marcado para <strong>' .$old_schedule->date . ' ' . $old_schedule->time . ' </strong> foi cancelado por '. \Auth::user()->full_name.' às '. Carbon::now()->format('d/m/Y H:i:s') .'.';
        $data['messageSubject'] = 'Cancelamento de horário';


        //Notify the client
        if(\Auth::user()->role == 'professional'){

            event(new ClientNotification($schedule->client->id, ['type' => 'cancel_schedule', 'payload' => $schedule]));

            event(new CompanyNotification($schedule->company_id, ['type' => 'cancel_schedule_by_professional', 'payload' => $schedule]));

            \Mail::to($schedule->client->email, $schedule->client->full_name)->queue(new DefaultEmail($data, ['client-schedule-cancel']));
        }

        //Notify the company
        if(\Auth::user()->role == 'client'){

            event(new CompanyNotification($schedule->company_id, ['type' => 'cancel_schedule', 'payload' => $schedule]));

            \Mail::to($schedule->professional->email, $schedule->professional->full_name)->queue(new DefaultEmail($data));
        }



        return response()->json([
            'message' => 'Canceled.',
            'schedule' => $schedule->load(['company', 'category', 'subscription' => function($query){
                $query->select('id','client_id', 'plan_id', 'start_at', 'expire_at');
            }, 'subscription.client', 'subscription.plan', 'professional', 'company' ])
        ]);
    }

    /**
     * Remove all schedules from invoice.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {
        $destroyeds = Schedule::where('invoice_id',$request->get('invoice_id'))->delete();

        if($destroyeds){
            return response()->json([
                'message' => 'Schedules destroyed.',
            ]);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function byInvoice($id)
    {
        $schedules = Schedule::with(['professional'])->where('invoice_id', $id)->get();

        return response()->json(['schedules' => $schedules]);
    }

    /**
     * Get Schedules by professional and date
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function schedules_by_professional_and_date(Request $request)
    {
        $company = Company::where('id', $request->get('company_id'))->with(['professionals' => function($query) use ($request){
            $query->select('id', 'name', 'last_name');
            $query->with(['schedules' => function($querytwo) use ($request){
                $querytwo->with(['subscription.client']);
                $querytwo->where('date', '>=', $request->get('init'));
                $querytwo->where('date', '<=', $request->get('end'));
                $querytwo->orderBy('date', 'ASC');
            }]);
        }])->first();

        return response()->json(['professionals_with_schedules' => $company->professionals]);
    }

}
