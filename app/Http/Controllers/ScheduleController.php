<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
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
        $schedules = Schedule::where('company', $id)->with(['client', 'plan'])->get();

        return response()->json(['schdules' => $schedules]);
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