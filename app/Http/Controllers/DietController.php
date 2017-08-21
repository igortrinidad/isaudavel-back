<?php

namespace App\Http\Controllers;

use App\Models\Diet;
use Illuminate\Http\Request;

class DietController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $diets = Diet::where('client_id', $request->get('client_id'))->with('from')->get();

        return response()->json(['diets' => $diets]);
    }

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds(Request $request)
    {
        $diets = Diet::where('client_id', $request->get('client_id'))->with('from')->onlyTrashed()->get();

        return response()->json(['diets_destroyeds' => $diets]);
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

        $diet = Diet::create($request->all());

        return response()->json([
            'message' => 'Diet created.',
            'diet' => $diet->fresh(['from'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $diet = Diet::find($request->get('diet_id'));

        return response()->json(['data' => $diet]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $diet = tap(Diet::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'diet updated.',
            'diet' => $diet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $destroyed = Diet::destroy($request->get('diet_id'));

        if($destroyed){
            return response()->json([
                'message' => 'diet destroyed.',
                'id' => $request->get('diet_id')
            ]);
        }

        return response()->json([
            'message' => 'diet not found.',
        ], 404);

    }

    /**
     * UNRemove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy(Request $request)
    {
        $undestroyed = Diet::withTrashed()
        ->where('id', $request->get('diet_id'))
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'diet undestroyed.',
                'id' => $request->get('diet_id')
            ]);
        }

        return response()->json([
            'message' => 'diet not found.',
        ], 404);

    }
}
