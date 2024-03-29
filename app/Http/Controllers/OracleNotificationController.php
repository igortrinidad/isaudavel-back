<?php

namespace App\Http\Controllers;

use App\Models\OracleNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OracleNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = OracleNotification::where('oracle_user_id', \Auth::user()->id)->with('from')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return response()->json(custom_paginator($notifications, 'notifications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notification = OracleNotification::create($request->all());

        return response()->json([
            'message' => 'Notification created.',
            'notification' => $notification->fresh()
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
        $notification = OracleNotification::find($id);

        return response()->json(['notification' => $notification]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $notification = tap(OracleNotification::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Notification updated.',
            'notification' => $notification
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
        //
    }

    /**
     * Mark a notification as readed.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function markReaded($id)
    {

        $notification = tap(OracleNotification::find($id))->update(['is_readed' => true, 'readed_at' => Carbon::now()])->fresh();

        return response()->json([
            'message' => 'Notification readed.',
            'notification' => $notification->load('from')
        ]);
    }

    /**
     * Mark all notifications as readed.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function markAllReaded(Request $request)
    {

        $notifications = OracleNotification::where('oracle_user_id', $request->get('id'))
            ->where('is_readed', false)
            ->get();

        foreach($notifications as $notification) {
            $notification->is_readed = true;
            $notification->readed_at = Carbon::now();
            $notification->save();
        }

        return response()->json([
            'message' => 'Notifications readed.',
            'success' => true,
            'readed_notifications' => $notifications->count()
        ]);
    }
}
