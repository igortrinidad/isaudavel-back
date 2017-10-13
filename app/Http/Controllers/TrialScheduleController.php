<?php

namespace App\Http\Controllers;

use App\Models\TrialSchedule;
use Illuminate\Http\Request;

class TrialScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trial_schedules = TrialSchedule::where('company_id', $request->get('company_id'))
            ->where('client_id', $request->get('client_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['trial_schedules' => $trial_schedules]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trial_schedule = TrialSchedule::create($request->all());

        return response()->json([
            'message' => 'Trial schedule created.',
            'trial_schedule' => $trial_schedule->fresh()
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
        $trial_schedule = TrialSchedule::with('company', 'client', 'category', 'professional')->find($id);

        return response()->json(['trial_schedule' => $trial_schedule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $trial_schedule = tap(TrialSchedule::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Trial schedule updated.',
            'trial_schedule' => $trial_schedule
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
        $destroyed = TrialSchedule::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Trial schedule destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Trial schedule not found.',
        ], 404);

    }
}
