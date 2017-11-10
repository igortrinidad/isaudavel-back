<?php

namespace App\Http\Controllers;

use App\Models\ClientSubscription;
use App\Models\Invoice;
use App\Models\Schedule;
use Carbon\Carbon;
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

        //Update existing schedules
        if($request->has('update_schedules') && $request->get('update_schedules') && count($subscription->workdays)){
            //get last invoice
            $last_invoice = Invoice::where('subscription_id', $subscription->id)->latest()->first();

           if($last_invoice){
               //invoice schedules
               $schedules = Schedule::where('invoice_id', $last_invoice->id)->orderBy('date')->get();

               foreach($schedules as $schedule){

                   $schedule_date = Carbon::createFromFormat('d/m/Y H:i:s', $schedule->date.''.$schedule->time );

                   //Update schedule only if is future
                   if($schedule_date->isFuture()){

                       $schedule->delete();

                   }
               }

               //generate new schedules
               $start = Carbon::createFromFormat('d/m/Y', $subscription->start_at);
               $end = Carbon::createFromFormat('d/m/Y', $subscription->expire_at);

               $new_schedules = Schedule::where('invoice_id', $last_invoice->id)->orderBy('date')->get();

               //Limiteded schedules quantity
               if ($subscription->plan->limit_quantity) {
                   for ($start; count($new_schedules) < $subscription->quantity; $start->addDays(1, 'days')) {

                       // get dow index
                       $dow_index = null;
                       foreach($subscription->workdays as $key => $workday) {

                           if($workday['dow'] == $start->dayOfWeek){
                               $dow_index = $key;
                           }
                       }

                       if ($dow_index > -1 && $subscription->workdays[$dow_index]['dow'] == $start->dayOfWeek) {

                           $schedule_data = [
                               'subscription_id' => $subscription->id,
                               'company_id' => $subscription->company_id,
                               'category_id' => $subscription->plan->category_id,
                               'date' => $start->format('d/m/Y'),
                               'time' => $subscription->workdays[$dow_index]['init'],
                               'professional_id' => $subscription->workdays[$dow_index]['professional_id'],
                               'invoice_id' => $last_invoice->id
                           ];

                           $new_schedule = Schedule::where([
                               'subscription_id' => $subscription->id,
                               'company_id' => $subscription->company_id,
                               'category_id' => $subscription->plan->category_id,
                               'date' => $start->format('Y-m-d'),
                               'time' => $subscription->workdays[$dow_index]['init'] . ':00',
                               'professional_id' => $subscription->workdays[$dow_index]['professional_id'],
                               'invoice_id' => $last_invoice->id])->first();

                           if (!$new_schedule && !$start->isPast()) {
                               $new_schedule = Schedule::create($schedule_data);
                               $new_schedules->push($new_schedule);
                           }
                       }
                   }
               }

               //Schedules by period
               if (!$subscription->plan->limit_quantity) {
                   for ($start; $start <= $end; $start->addDays(1, 'days')) {

                       // get dow index
                       $dow_index = null;
                       foreach($subscription->workdays as $key => $workday) {

                           if($workday['dow'] == $start->dayOfWeek){
                               $dow_index = $key;
                           }
                       }

                       if ($dow_index > -1 && $subscription->workdays[$dow_index]['dow'] == $start->dayOfWeek) {

                           $schedule_data = [
                               'subscription_id' => $subscription->id,
                               'company_id' => $subscription->company_id,
                               'category_id' => $subscription->plan->category_id,
                               'date' => $start->format('d/m/Y'),
                               'time' => $subscription->workdays[$dow_index]['init'],
                               'professional_id' => $subscription->workdays[$dow_index]['professional_id'],
                               'invoice_id' => $last_invoice->id
                           ];

                           $new_schedule = Schedule::where([
                               'subscription_id' => $subscription->id,
                               'company_id' => $subscription->company_id,
                               'category_id' => $subscription->plan->category_id,
                               'date' => $start->format('Y-m-d'),
                               'time' => $subscription->workdays[$dow_index]['init'] . ':00',
                               'professional_id' => $subscription->workdays[$dow_index]['professional_id'],
                               'invoice_id' => $last_invoice->id])->first();

                           if (!$new_schedule && !$start->isPast()) {
                               $new_schedule = Schedule::create($schedule_data);
                               $new_schedules->push($new_schedule);
                           }
                       }
                   }
               }
           }
        }

        /*
        DESABILITADO POR ENQUANTO
        //Send Mail
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Atualização de assinatura</h4>';
        $data['messageOne'] = 'Sua assinatura da empresa '. $subscription->company->name .' referente ao plano '. $subscription->plan->name .' foi atualizada em '. $subscription->updated_at->format('d/m/Y h:i:s') .'.';
        $data['messageTwo'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';
        $data['messageSubject'] = 'Alteração de assinatura';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $subscription){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($subscription->client->email, $subscription->client->full_name)->subject($data['messageSubject']);
        });
        */

        return response()->json([
            'message' => 'Subscription updated.',
            'subscription' => $subscription->load(['plan', 'invoices' => function($query){
                $query->orderBy('expire_at', 'ASC');
            }, 'invoices.schedules'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $subscription = ClientSubscription::find($request->get('subscription_id') );

        $schedules = Schedule::where('subscription_id', $request->get('subscription_id'))->get();

        foreach($schedules as $schedule){
            $schedule->delete();
        }

        $subscription->delete();

        if($subscription){
            return response()->json([
                'message' => 'Subscription destroyed.',
                'id' => $request->get('subscription_id')
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
