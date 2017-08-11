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
        $evaluations = Evaluation::where('client_id', $id)
            ->with('from')
            ->orderBy('created_at')
            ->get();

        return response()->json(['evaluations' => $evaluations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!empty($request->file('file'))){

            $file = $request->file('file');

            $fileName = bin2hex(random_bytes(16)) . '.' . $file->getClientOriginalExtension();

            $filePath = 'client/evaluations/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

            $request->merge([
                'path' => $filePath,
            ]);
        }

        $request->merge([
            'client_id' => $request->get('client_id'), 
            'items' => json_encode($request->get('items')), 
            'observation' => $request->get('observation'),
            'created_by_id' => \Auth::user()->id,
            'created_by_type' => get_class(\Auth::user())
        ]);

        $evaluation = Evaluation::create($request->all());

        return response()->json([
            'message' => 'evaluation created.',
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

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds($id)
    {
        $exams = Evaluation::where('client_id', $id)->with('from')->onlyTrashed()->get();

        return response()->json(['exams_destroyeds' => $exams]);
    }
}
