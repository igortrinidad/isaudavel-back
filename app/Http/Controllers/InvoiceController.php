<?php

namespace App\Http\Controllers;

use App\Mail\DefaultEmail;
use App\Models\Invoice;
use App\Models\Schedule;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = Invoice::where('company_id', $request->get('company_id'))
        ->where('expire_at', '>=', $request->get('init'))
        ->where('expire_at', '<=', $request->get('end'))
        ->with(['subscription.client', 'subscription.plan', 'subscription.plan.category'])
            ->whereHas('subscription', function($query) use ($request){
                $query->whereHas('client', function($querytow) use ($request){
                    $querytow->where('name', 'LIKE', '%' . $request->get('search') . '%');
                    $querytow->orWhere('email', $request->get('search'));
                });
        })->orderBy('expire_at', 'ASC')->paginate(10);

        return response()->json(custom_paginator($invoices, 'invoices'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function clientList(Request $request)
    {
        $invoices = Invoice::where('company_id', $request->get('company_id'))
            ->where('expire_at', '>=', $request->get('init'))
            ->where('expire_at', '<=', $request->get('end'))
            ->whereHas('subscription', function ($query) use ($request) {
                $query->where('client_id', $request->get('client_id'));
            })
            ->with(['subscription.client', 'subscription.plan'])
            ->orderBy('expire_at', 'DESC')->paginate(10);

        return response()->json(custom_paginator($invoices, 'invoices'));
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

        //Email
        $data = [];
        $data['align'] = 'center';

        $schedules = '';

        foreach($invoice->schedules as $schedule){
            $schedules .= $schedule->date. ' ' .$schedule->time. '<br>';
        }

        $data['messageTitle'] = '<h4>Novo plano</h4>';
        $data['messageOne'] = 'A empresa '. $invoice->company->name. ' acabou de adicionar um plano de <b>' .$invoice->subscription->plan->category->name . '</b> para você através da plataforma iSaudavel no valor de <b>R$' . $invoice->value . '</b> com vencimento para <b>' . $invoice->expire_at . '</b>.<hr>
        <p>Agendamentos</p>
        <b>' .$schedules . '</b>';

        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageFour'] = 'Este email é apenas informativo - o pagamento referente à este plano deverá ser realizado diretamente com a empresa.
        <br>
        Desconsidere esta mensagem caso o pagamento já tenha sido efetuado.';

        $data['messageSubject'] = 'iSaudavel: Fatura recebida';

        //Send mail
        \Mail::to($invoice->subscription->client->email, $invoice->subscription->client->full_name)->queue(new DefaultEmail($data, ['new-invoice']));

        return response()->json([
            'message' => 'Invoice created.',
            'invoice' => $invoice->fresh(['subscription.client', 'subscription.plan'])
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

        //Send Mail
        $data = [];
        $data['align'] = 'center';

        if($invoice->is_canceled && !$invoice->is_confirmed){
            //Cancelado
            $status = '<span style="background-color: #E14E48; padding: 10px; border-radius: 4px;">Cancelado</span>';
        } else if(!$invoice->is_canceled && $invoice->is_confirmed) {
            //Confirmado
            $status = '<span style="background-color: #00A268; padding: 10px; border-radius: 4px;">Confirmado</span>';
        } else {
            //Pendente
            $status = '<span style="background-color: #FEC059; padding: 10px; border-radius: 4px;">Pendente</span>';
        }

        $data['messageTitle'] = '<h4>Fatura atualizada</h4>';
        $data['messageOne'] = 'Sua fatura com vencimento para '. $invoice->expire_at. ' foi atualizada em '. $invoice->updated_at->format('d/m/Y h:i:s') . '.
        <br>
        <h4>Status da fatura</h4>' . $status;
        $data['messageTwo'] = 'Acesse online em https://app.isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Alteração de fatura';
        
        //Send mail
        \Mail::to($invoice->subscription->client->email, $invoice->subscription->client->full_name)->queue(new DefaultEmail($data, ['invoice-update']));

        return response()->json([
            'message' => 'Invoice updated.',
            'invoice' => $invoice
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @internal param $id
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::where('company_id', $request->get('company_id'))->where('id', $request->get('invoice_id'))->first();

        $invoice->schedules()->delete();

        if ($invoice->delete()) {
            return response()->json([
                'message' => 'Invoice destroyed.',
            ]);
        }

        return response()->json([
            'message' => 'Invoice not found.',
        ], 404);

    }
}
