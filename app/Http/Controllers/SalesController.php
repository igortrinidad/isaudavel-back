<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Carbon\Carbon;
use Illuminate\Http\Request;


class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('oracle.dashboard.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function dashboardData(Request $request)
    {
        $period = $request->get('period');

        $contacts_created = 0;
        $tasks = 0;
        $emails = 0;
        $calls = 0;
        $notes = 0;

        /**
         * Hubspot uses epoch timestamp so we need convert those dates
         */

        //Default is today
        $start = Carbon::now()->startOfDay()->getTimestamp() * 1000;
        $end = Carbon::now()->endOfDay()->getTimestamp() * 1000;

        if($period == 'week'){
            $start = Carbon::now()->startOfWeek()->startOfDay()->getTimestamp() * 1000;
            $end = Carbon::now()->endOfWeek()->endOfDay()->getTimestamp() * 1000;


        }

        if($period == 'month'){
            $start = Carbon::now()->startOfMonth()->startOfDay()->getTimestamp() * 1000;
            $end = Carbon::now()->endOfMonth()->endOfDay()->getTimestamp() * 1000;
        }


        $owners = \HubSpot::owners()->all()->data;

        $last_contacts = \HubSpot::contacts()->recent(['count' => 200])->data->contacts;

        foreach($last_contacts as $contact){
           if( $contact->{'identity-profiles'}[0]->{'saved-at-timestamp'} >= $start && $contact->{'identity-profiles'}[0]->{'saved-at-timestamp'} <= $end)
           {
               $contacts_created++;
           }
        }

        $last_engagements = $this->getAllEngagements($start);

        $engagements = [];
        foreach ($last_engagements as $engagement) {

            if($engagement->engagement->createdAt >= $start && $engagement->engagement->createdAt <= $end )
            {
                if($engagement->engagement->type == 'TASK'){
                    $tasks++;
                }

                if($engagement->engagement->type == 'EMAIL'){
                    $emails++;
                }

                if($engagement->engagement->type == 'CALL'){
                    $calls++;
                }

                if($engagement->engagement->type == 'NOTE'){
                    $notes++;
                }

                $owner_name = '';

                foreach ($owners as $owner) {
                    if ($owner->ownerId == $engagement->engagement->ownerId) {
                        $owner_name = $owner->firstName . ' ' . $owner->lastName;

                    }

                    $engagement->metadata->username = $owner_name;

                    $engagement->engagement->created_at = date('d/m/Y H:i:s', $engagement->engagement->createdAt / 1000);

                    if(isset($engagement->metadata->body)){
                        $engagement->metadata->body = str_limit(strip_tags($engagement->metadata->body), 100);
                    }

                }

                $engagements[] = $engagement;
            }

        }

        //unconvert dates

        $start = date('Y-m-d H:i:s', $start / 1000);
        $end = date('Y-m-d H:i:s', $end / 1000);

        $professionals = Professional::whereBetween('created_at', [$start, $end])->get()->count();

        $widgets_data = new \stdClass();

        $widgets_data->professionals = $professionals;
        $widgets_data->notes = $notes;
        $widgets_data->tasks = $tasks;
        $widgets_data->emails = $emails;
        $widgets_data->calls = $calls;
        $widgets_data->contacts_created = $contacts_created;

        return response()->json(['widgets_data' => $widgets_data, 'last_engagements' => $engagements]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function getAllEngagements($start, $offset = 0){

        $engagements = [];

        $response = $this->callEngagements($start);

        while ($response->hasMore) {

            foreach ($response->results as $engagement) {
                $engagements [] = $engagement;
            }

          $response = $this->callEngagements($start, $response->offset);
        }

        if(!$response->hasMore){
            foreach ($response->results as $engagement) {
                $engagements [] = $engagement;
            }
        }

        return $engagements;
    }

    function callEngagements($start, $offset = 0){
        $engagementsEndpoint = 'https://api.hubapi.com/engagements/v1/engagements/recent/modified';

        return hubspot_support($engagementsEndpoint, ['since' => $start, 'offset' => $offset, 'count' => 100])->data;
    }
}
