<?php

namespace App\Http\Controllers;

use App\Models\CategoryCalendarSetting;
use App\Models\ClientSubscription;
use Illuminate\Http\Request;

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
        $calendar_setting = tap(CategoryCalendarSetting::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Calendar setting updated.',
            'calendar_setting' => $calendar_setting
        ]);
    }
}
