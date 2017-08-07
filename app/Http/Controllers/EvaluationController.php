<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $trainnings = Evaluation::where('client_id', $id)
            ->with('professional', 'photos')
            ->orderBy('created_at')
            ->get();

        return response()->json(['evaluations' => $trainnings]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evaluation = Evaluation::create($request->all());

        return response()->json([
            'message' => 'Evaluation created.',
            'evaluation' => $evaluation->fresh(['from'])
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
        $evaluation = Evaluation::find($id);

        return response()->json(['data' => $evaluation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $evaluation = tap(Evaluation::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Evaluation updated.',
            'evaluation' => $evaluation
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
        $destroyed = Evaluation::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Evaluation destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Evaluation not found.',
        ], 404);

    }
}
