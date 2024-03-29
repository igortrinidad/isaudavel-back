<?php

namespace App\Http\Controllers;

use App\Models\ClientPhoto;
use Illuminate\Http\Request;

class ClientPhotoController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $photos = ClientPhoto::where('client_id', $request->get('client_id'))
            ->orderBy('created_at', 'DESC')
            ->paginate(12);

        return response()->json(custom_paginator($photos));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_profile(Request $request)
    {
        ClientPhoto::where('client_id', $request->get('client_id'))->update(['is_profile' => false]);

        $photo = ClientPhoto::where('client_id', $request->get('client_id'))->where('id', $request->get('photo_id'))->update(['is_profile' => true]);

        return response()->json(['message' => 'Profile updated']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_profile = $request->get('is_profile') == 'true' ? true : false;

        $image = $request->file('file');

        $fileName = bin2hex(random_bytes(16)) . '.' . $image->getClientOriginalExtension();

        $filePath = 'client/photo/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($image), 'public');

        $request->merge(['path' => $filePath, 'is_profile' => $is_profile]);

        $clientPhoto = ClientPhoto::create($request->all());

        $response = [
            'message' => 'Client photo created.',
            'photo'    => $clientPhoto->fresh()->toArray(),
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
        $photo = tap(ClientPhoto::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Client photo updated.',
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
    public function destroy(Request $request)
    {
        $photo = ClientPhoto::find($request->get('photo_id'));

        \Storage::disk('media')->delete($photo->path);

        $destroyed = $photo->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Client photo destroyed.',
                'id' => $request->get('photo_id')
            ]);
        }

        return response()->json([
            'message' => 'Client photo not found.',
        ], 404);
    }
}
