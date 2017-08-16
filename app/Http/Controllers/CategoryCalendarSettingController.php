<?php

namespace App\Http\Controllers;

use App\Models\CategoryCalendarSetting;
use Illuminate\Http\Request;

class CategoryCalendarSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
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
            'diet' => $calendar_setting
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
