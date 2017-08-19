<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Schedule;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = Invoice::where('company_id', $request->get('company_id'))
        ->where('expire_at', '>', $request->get('init'))
        ->where('expire_at', '<', $request->get('end'))
        ->with(['subscription.client', 'subscription.plan', 'schedules'])
            ->whereHas('subscription', function($query) use ($request){
                $query->whereHas('client', function($querytow) use ($request){
                    $querytow->where('name', 'LIKE', '%' . $request->get('search') . '%');
                    $querytow->orWhere('email', $request->get('search'));
                });
        })->orderBy('expire_at', 'ASC')->limit(20)->get();

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $invoice = Invoice::create($request->all());

        //Store schedules
        foreach($request->get('schedules') as $schedule){

            $schedule['invoice_id'] = $invoice->id;

            Schedule::create($schedule);
        }

        return response()->json([
            'message' => 'Invoice created.',
            'invoice' => $invoice->fresh(['schedules.professional'])
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
        $invoice = Invoice::find($id);

        return response()->json(['invoice' => $invoice]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoice = tap(Invoice::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Invoice updated.',
            'invoice' => $invoice
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
        $destroyed = Invoice::destroy($id);

        if ($destroyed) {
            return response()->json([
                'message' => 'Invoice destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Invoice not found.',
        ], 404);

    }
}
