<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalSubscription;
use Illuminate\Http\Request;

class ProfessionalSubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subscription = ProfessionalSubscription::create($request->all());

        return response()->json([
            'message' => 'Professional subscription created.',
            'subscription' => $subscription->fresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $subscription = tap(ProfessionalSubscription::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Professional subscription ',
            'subscription' => $subscription
        ]);
    }
}
