<?php

namespace App\Http\Controllers;

use App\Models\ExamAttachment;
use Illuminate\Http\Request;

class ExamAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attachments = ExamAttachment::orderBy('created_at', 'desc')->paginate(10);

        return response()->json(custom_paginator($attachments));
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

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

        $filePath = 'exam/attachment/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

        //merge file path on request
        $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);

        $attachment = ExamAttachment::create($request->all());

        $response = [
            'message' => 'Attachment created.',
            'attachment'    => $attachment->fresh()->toArray(),
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
        $attachment = tap(ExamAttachment::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Attachment updated.',
            'attachment' => $attachment
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
        $attachment = ExamAttachment::find($id);

        \Storage::disk('media')->delete($attachment->path);

        $destroyed = $attachment->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Attachment destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Attachment not found.',
        ], 404);
    }
}
