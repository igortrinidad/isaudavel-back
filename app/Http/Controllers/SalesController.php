<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
        $incoming_emails = 0;
        $emails = 0;
        $calls = 0;
        $notes = 0;
        $meetings = 0;
        $companies = 0;

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

        $companies_data  = $this->getAllCompanies($owners);

        foreach($companies_data['companies'] as $company){
            if( $company->properties->createdate->value >= $start && $company->properties->createdate->value <= $end)
            {
                $companies++;
            }
        }

        $companies_chart_data = $companies_data['companies_created'];

        $engagements = [];

        $chartsData = [];
        foreach ($last_engagements as $engagement) {

            if($engagement->engagement->createdAt >= $start && $engagement->engagement->createdAt <= $end )
            {
                $owner_name = '';
                //get owner

                $owner = $this->searchInArray($owners, ['ownerId' => $engagement->engagement->ownerId]);

                if ($owner->ownerId == $engagement->engagement->ownerId) {
                    $owner_name = $owner->firstName . ' ' . $owner->lastName;

                    $chartsData[$engagement->engagement->ownerId]['label'] = $owner_name;
                    if (!array_key_exists($engagement->engagement->type, $chartsData[$engagement->engagement->ownerId])) {
                        $chartsData[$engagement->engagement->ownerId][$engagement->engagement->type] = 1;
                    } else {
                        $chartsData[$engagement->engagement->ownerId][$engagement->engagement->type]++;
                    }

                }

                $engagement->metadata->username = $owner_name;

                $engagement->engagement->created_at = date('d/m/Y H:i:s', $engagement->engagement->createdAt / 1000);

                if(isset($engagement->metadata->body)){
                    $engagement->metadata->body = str_limit(strip_tags($engagement->metadata->body), 100);
                }

                if($engagement->engagement->type == 'TASK'){
                    $tasks++;
                }

                if($engagement->engagement->type == 'EMAIL'){
                    $emails++;
                }

                if($engagement->engagement->type == 'INCOMING_EMAIL'){
                    $incoming_emails++;
                }

                if($engagement->engagement->type == 'CALL'){
                    $calls++;
                }

                if($engagement->engagement->type == 'NOTE'){
                    $notes++;
                }

                if($engagement->engagement->type == 'MEETING'){
                    $meetings++;
                }

                $engagements[] = $engagement;
            }

        }

        //standardize the chart data

        $newChartsData = [];
        foreach($chartsData as $key => $data){

            if(!array_key_exists('TASK', $data)){
               array_set($data, 'TASK', 0);
            }

            if(!array_key_exists('EMAIL', $data)){
                array_set($data, 'EMAIL', 0);
            }

            if(!array_key_exists('INCOMING_EMAIL', $data)){
                array_set($data, 'INCOMING_EMAIL', 0);
            }

            if(!array_key_exists('CALL', $data)){
                array_set($data, 'CALL', 0);
            }

            if(!array_key_exists('NOTE', $data)){
                array_set($data, 'NOTE', 0);
            }

            if(!array_key_exists('MEETING', $data)){
                array_set($data, 'MEETING', 0);
            }


            $newChartsData[] = $data;

        }

        //unconvert dates

        $start = date('Y-m-d H:i:s', $start / 1000);
        $end = date('Y-m-d H:i:s', $end / 1000);

        $professionals = Professional::whereBetween('created_at', [$start, $end])->get()->count();

        $last_companies_isudavel = Company::orderBy('created_at', 'desc')->with('owner')->take(6)->get();

        $last_companies_hubspot = array_reverse(array_slice($companies_data['companies'], -6, 6));

        $widgets_data = new \stdClass();

        $widgets_data->professionals = $professionals;
        $widgets_data->notes = $notes;
        $widgets_data->tasks = $tasks;
        $widgets_data->emails = $emails;
        $widgets_data->incoming_emails = $incoming_emails;
        $widgets_data->calls = $calls;
        $widgets_data->meetings = $meetings;
        $widgets_data->companies = $companies;
        $widgets_data->contacts_created = $contacts_created;

        $engagements = array_slice($engagements, 0, 30);

        return response()->json([
            'widgets_data' => $widgets_data,
            'last_engagements' => $engagements,
            'charts_data' => $newChartsData,
            'last_companies_hubspot' => $last_companies_hubspot,
            'last_companies_isaudavel' => $last_companies_isudavel
        ]);
    }

    /**
     * Fetch and accumulate all engagements
     *
     * @return array
     */
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

    /**
     * Call the hubspot engagements endpoint
     *
     * @param $start
     * @param int $offset
     * @return mixed
     */
    function callEngagements($start, $offset = 0){
        $engagementsEndpoint = 'https://api.hubapi.com/engagements/v1/engagements/recent/modified';

        return hubspot_support($engagementsEndpoint, ['since' => $start, 'offset' => $offset, 'count' => 100])->data;
    }

    /**
     * Fetch and accumulate all companies
     *
     * @param $owners
     * @return array
     */
    function getAllCompanies($owners){
        $response = $this->callCompanies();

        $companies_created = [];

        while ($response->{'has-more'}) {

            foreach ($response->companies as $company) {

                $companies [] = $company;
            }

            $response = $this->callCompanies($response->{'offset'});
        }

        if(!$response->{'has-more'}){
            foreach ($response->companies as $company) {

                $company->name = $company->properties->name->value;
                $company->created_at = date('d/m/Y H:i:s', $company->properties->createdate->value / 1000);

                //get creator status
                if (!array_key_exists($company->properties->name->sourceId, $companies_created)) {
                    //get owner
                    $owner = $this->searchInArray($owners, ['email' => $company->properties->name->sourceId]);

                    if($owner){
                        $company->owner = $owner->firstName.' '. $owner->lastName;
                        $companies_created[$company->properties->name->sourceId]['label'] = $owner->firstName.' '. $owner->lastName;
                    }
                    $companies_created[$company->properties->name->sourceId]['total'] = 1;
                } else {
                    $companies_created[$company->properties->name->sourceId]['total']++;
                }

                if($owner){
                    $company->owner = $owner->firstName.' '. $owner->lastName;
                }

              $companies [] = $company;
            }
        }

        //unset data created by hubspot crawlers
        unset($companies_created['BidenPropertyMappings']);

        return ['companies' => $companies, 'companies_created' => $companies_created];
    }

    /**
     * Call the hubspot companies endpoint
     *
     * @param int $offset
     * @return mixed
     */
    function callCompanies($offset = 0){
        return  \HubSpot::companies()->all(['limit' => 250, 'offset' => $offset, 'properties' => ['name', 'website', 'createdate']])->data;
    }

    /**
     * @param $array
     * @param $condition
     * @return string
     */
    function searchInArray($array, $condition){

        foreach ($array as $arrItem) {

            //item is a object (stdClass)
            if(is_object($arrItem)){

                foreach ($condition as $key => $value) {

                    if (isset($arrItem->{$key}) && $arrItem->{$key} !== $value) {
                        continue 2;
                    }

                    if (isset($arrItem->{$key}) && $arrItem->{$key} == $value) {

                        return $arrItem;
                    }
                }
            }


            if(is_array($arrItem)){
                foreach ($condition as $key => $value) {

                    if (isset($arrItem[$key]) && $arrItem[$key] !== $value) {
                        continue 2;
                    }

                    if (isset($arrItem[$key]) && $arrItem[$key] === $value) {
                        return $arrItem;
                    }


                }
            }
            return null;
        }
    }
}
