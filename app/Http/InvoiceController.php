<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $invoices = Invoice::where('company', $id)->with(['client', 'plan'])->get();

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = Invoice::find($id);

        return response()->json(['invoice' => $invoices]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $invoices = Invoice::create($request->all());

        return response()->json([
            'message' => 'Invoice created.',
            'invoice' => $invoices->fresh()
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
        $invoices = tap(Invoice::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Invoice updated.',
            'invoice' => $invoices
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

        if($destroyed){
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
