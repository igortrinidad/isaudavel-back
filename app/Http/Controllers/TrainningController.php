<?php

namespace App\Http\Controllers;

use App\Models\Trainning;
use Illuminate\Http\Request;

class TrainningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $trainnings = Trainning::where('client_id', $id)->with('from')->get();

        return response()->json(['trainnings' => $trainnings]);
    }

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds($id)
    {
        $trainnings = Trainning::where('client_id', $id)->with('from')->onlyTrashed()->get();

        return response()->json(['trainnings_destroyeds' => $trainnings]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->merge(['created_by_id' => \Auth::user()->id, 'created_by_type' => get_class(\Auth::user())]);

        $trainning = Trainning::create($request->all());

        return response()->json([
            'message' => 'Trainning created.',
            'trainning' => $trainning->fresh(['from'])
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
        $trainning = Trainning::find($id);

        return response()->json(['data' => $trainning]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $trainning = tap(Trainning::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Trainning updated.',
            'trainning' => $trainning
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
        $destroyed = Trainning::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Trainning destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Trainning not found.',
        ], 404);

    }

    public function undestroy($id)
    {
        $undestroyed = Trainning::withTrashed()
        ->where('id', $id)
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'trainning undestroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'trainning not found.',
        ], 404);

    }
}
