<?php

namespace App\Http\Controllers;

use App\Events\OracleNotification;
use Illuminate\Http\Request;

class HubspotController extends Controller
{

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
