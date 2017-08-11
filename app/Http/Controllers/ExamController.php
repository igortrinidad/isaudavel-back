<?php

namespace App\Http\Controllers;

use App\Models\Exam;
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
        $exams = Exam::where('client_id', $id)->with('from')->get();

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

        $request->merge(['created_by_id' => \Auth::user()->id, 'created_by_type' => get_class(\Auth::user())]);

        $exam = Exam::create($request->all());

        return response()->json([
            'message' => 'Exam created.',
            'exam' => $exam->fresh(['from'])
        ]);
    }


    public function storetwo(Request $request)
    {

        $request->merge(['created_by_id' => \Auth::user()->id, 'created_by_type' => get_class(\Auth::user())]);

        $file = $request->file('file');

        $fileName = bin2hex(random_bytes(16)) . '.' . $file->getClientOriginalExtension();

        $filePath = 'client/exam/' . $fileName;
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

        $request->merge([
            'client_id' => $request->get('client_id'), 
            'type' => $request->get('type'), 
            'obversation' => $request->get('obversation'),
            'path' => $filePath, 
            'filename' => $originalName, 
            'extension' => $extension
        ]);

        $exam = Exam::create($request->all());

        return response()->json([
            'message' => 'Exam created.',
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
}
