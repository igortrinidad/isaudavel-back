<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttachment;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $exams = Exam::where('client_id', $id)->with(['from', 'attachments'])->get();

        return response()->json(['exams' => $exams]);
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

        $exam = Exam::create($request->all());

        //update attachments
        if (array_key_exists('attachments', $request->all())) {
            foreach ($request->get('attachments') as $photo) {
                ExamAttachment::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'exam created.',
            'exam' => $exam->fresh(['from'])
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
        $exam = Exam::find($id);

        return response()->json(['data' => $exam]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $exam = tap(Exam::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Exam updated.',
            'exam' => $exam
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
        $destroyed = Exam::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Exam destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Exam not found.',
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
        $exams = Exam::where('client_id', $id)->with('from')->onlyTrashed()->get();

        return response()->json(['exams_destroyeds' => $exams]);
    }

    /**
     * Restore a evaluation.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy($id)
    {
        $undestroyed = Exam::withTrashed()
        ->where('id', $id)
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'Exam undestroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Exam not found.',
        ], 404);

    }
}
