<?php

namespace App\Http\Controllers;

use App\Events\OracleNotification;
use Illuminate\Http\Request;


class HubspotController extends Controller
{

    /**
     * Get the authorization code from HubSpot
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function getCode(Request $request)
    {
        $code  = $request->get('code');

        if(!$code){
            return response()->json(['message' => 'Integration error: No authorization code provided.'], 500);
        }

        \Redis::set('hubspot_app_auth_code', $code);

        //Get the access and refresh tokens via CURL
        $service_url = 'https://api.hubapi.com/oauth/v1/token';
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'grant_type' => 'authorization_code',
            'client_id' => env('HUBSPOT_APP_CLIENT_ID'),
            'client_secret' => env('HUBSPOT_APP_CLIENT_SECRET'),
            'redirect_uri' => env('HUBSPOT_APP_REDIRECT_URI'),
            'code' => $code
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post_data));

        $curl_response = json_decode(curl_exec($curl), true);

        \Redis::set('hubspot_app_access_token', $curl_response['access_token']);
        \Redis::set('hubspot_app_refresh_token', $curl_response['refresh_token']);

        return response()->json(['message' => 'Successfully integrated with HubSpot']);
    }

    /**
     * Receive HubSpot action hooks
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receive(Request $request)
    {
        foreach($request->all() as $event){

            if($event['subscriptionType'] == 'contact.creation')
            {
                $contact = \HubSpot::contacts()->getById($event['objectId']);

                $name = isset($contact->properties->firstname) ?  $contact->properties->firstname->value : '';
                $last_name = isset($contact->properties->lastname) ? $contact->properties->lastname->value : '';
                $email = isset($contact->properties->email) ? $contact->properties->email->value : null;

                //Subscribe the contact on mailchimp
                if($email){
                    \Newsletter::subscribe($email, ['firstName' => $name, 'lastName'=> $last_name], 'isaudavel_professionals');
                }

                event( new OracleNotification(['type' => 'hubspot_contact_creation', 'payload' => ['name' => $name, 'last_name' => $last_name] ]));
            }

            if($event['subscriptionType']  == 'contact.deletion')
            {
                event( new OracleNotification(['type' => 'hubspot_contact_deletion', 'payload' => $event ]));
            }
        }

        return response()->json(['message' => 'Success']);
    }

    /**
     * New contact added on Hubspot
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newContact(Request $request)
    {
        event( new OracleNotification(['type' => 'hubspot_action', 'payload' => $request->all() ]));
        return response()->json(['message' => 'ok']);
    }


}
