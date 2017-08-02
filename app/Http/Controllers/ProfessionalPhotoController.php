<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalPhoto;
use Illuminate\Http\Request;

class ProfessionalPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = ProfessionalPhoto::where('professional_id', \Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        $image = $request->file('image');

        $fileName = bin2hex(random_bytes(16)) . '.' . $image->getClientOriginalExtension();

        $filePath = 'professional/photo/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($image), 'public');

        $request->merge(['path' => $filePath, 'professional_id' => \Auth::user()->id]);

        $professionalPhoto = ProfessionalPhoto::create($request->all());

        $response = [
            'message' => 'Professional photo created.',
            'photo'    => $professionalPhoto->fresh()->toArray(),
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
        $photo = tap(ProfessionalPhoto::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Professional photo updated.',
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
        $photo = ProfessionalPhoto::find($id);

        \Storage::disk('media')->delete($photo->path);

        $destroyed = $photo->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Professional photo destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Professional photo not found.',
        ], 404);
    }
}
