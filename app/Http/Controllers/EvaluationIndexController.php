<?php

namespace App\Http\Controllers;

use App\Models\EvaluationIndex;
use Illuminate\Http\Request;

class EvaluationIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evaluation_index = EvaluationIndex::all();

        return response()->json(['evaluation_index' => $evaluation_index]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->merge([
            'created_by_id' => \Auth::user()->id,
            'created_by_type' => get_class(\Auth::user())
        ]);

        $evaluation_index = EvaluationIndex::create($request->all());

        return response()->json([
            'message' => 'Evaluation Index created.',
            'evaluation_index' => $evaluation_index
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
        $evaluation_index = EvaluationIndex::find($request->get('exam_id'));

        return response()->json(['data' => $evaluation_index]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $evaluation_index = tap(EvaluationIndex::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Exam updated.',
            'evaluation_index' => $evaluation_index
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
        $destroyed = EvaluationIndex::destroy($request->get('exam_id'));

        if($destroyed){
            return response()->json([
                'message' => 'Exam destroyed.',
                'id' => $request->get('exam_id')
            ]);
        }

        return response()->json([
            'message' => 'Exam not found.',
        ], 404);

    }

}
