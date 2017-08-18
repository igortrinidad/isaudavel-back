<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $plans = Plan::with('category')->where('company_id', $id)->get();

        return response()->json(['plans' => $plans]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $plan = Plan::create($request->all());

        return response()->json([
            'message' => 'Plan created.',
            'plan' => $plan->fresh()
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
        $plan = Plan::find($id);

        return response()->json(['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $plan = tap(Plan::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Plan updated.',
            'plan' => $plan
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
        $destroyed = Plan::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Plan destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Plan not found.',
        ], 404);

    }

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds($id)
    {
        $plans = Plan::onlyTrashed()->get();

        return response()->json(['exams_destroyeds' => $plans]);
    }

    /**
     * Restore a evaluation.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy($id)
    {
        $undestroyed = Plan::withTrashed()
        ->where('id', $id)
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'Plan undestroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Plan not found.',
        ], 404);

    }
}
