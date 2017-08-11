<?php

namespace App\Http\Controllers;

use App\Models\EvaluationPhoto;
use Illuminate\Http\Request;

class EvaluationPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = EvaluationPhoto::orderBy('created_at', 'desc')->paginate(10);

        return response()->json(custom_paginator($photos));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');

        $fileName = bin2hex(random_bytes(16)) . '.' . $file->getClientOriginalExtension();

        $filePath = 'evaluation/photo/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

        $request->merge(['path' => $filePath]);

        $evaluationPhoto = EvaluationPhoto::create($request->all());

        $response = [
            'message' => 'Evaluation photo created.',
            'photo'    => $evaluationPhoto->fresh()->toArray(),
        ];

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $photo = tap(EvaluationPhoto::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Evaluation photo updated.',
            'photo' => $photo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = EvaluationPhoto::find($id);

        \Storage::disk('media')->delete($photo->path);

        $destroyed = $photo->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Evaluation photo destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Evaluation photo not found.',
        ], 404);
    }
}
