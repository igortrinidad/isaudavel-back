<?php

namespace App\Http\Controllers;

use App\Models\ClientSubscription;
use Illuminate\Http\Request;

class ClientSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $subscriptions = ClientSubscription::where('company', $id)->with(['client', 'plan'])->get();

        return response()->json(['client_subscriptions' => $subscriptions]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscription = ClientSubscription::find($id);

        return response()->json(['subscription' => $subscription]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $subscription = ClientSubscription::create($request->all());

        return response()->json([
            'message' => 'Subscription created.',
            'subscription' => $subscription->fresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $subscription = tap(ClientSubscription::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Subscription updated.',
            'subscription' => $subscription
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
        $destroyed = ClientSubscription::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Subscription destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Subscription not found.',
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
        $subscriptions = ClientSubscription::where('client_id', $id)->with('from')->onlyTrashed()->get();

        return response()->json(['exams_destroyeds' => $subscriptions]);
    }

    /**
     * Restore a evaluation.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy($id)
    {
        $undestroyed = ClientSubscription::withTrashed()
        ->where('id', $id)
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'Subscription undestroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Subscription not found.',
        ], 404);

    }
}
