<?php

namespace App\Http\Controllers;

use App\Models\EventComment;
use Illuminate\Http\Request;

class EventCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $comments = EventComment::where('event_id', $id)->with(['from'])->orderBy('created_at', 'desc')->paginate(15);

        return response()->json(custom_paginator($comments));
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'created_by_id' => \Auth::user()->id,
            'created_by_type' => get_class(\Auth::user())
        ]);

        $comment = EventComment::create($request->all());

        return response()->json([
            'message' => 'Comment created.',
            'comment' => $comment->fresh(['from'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if(\Auth::user()->id != $request->get('created_by_id')){
            return response()->json(['error' => 'Forbiden.'], 403);
        }

        $comment = EventComment::where('id', $request->get('id'))->update($request->all());

        return response()->json([
            'message' => 'Comment updated.',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(\Auth::user()->id != $request->get('created_by_id')){
            return response()->json(['error' => 'Forbiden.'], 403);
        }

        $comment = EventComment::where('id', $request->get('id'))->delete();

        return response()->json([
            'message' => 'Comment removed.',
        ], 200);
    }


}
