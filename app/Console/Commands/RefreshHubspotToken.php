<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshHubspotToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:refresh_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the app access token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hubspot_app_refresh_token = \Redis::get('hubspot_app_refresh_token');

        //Refresh the Hubspot app token via CURL
        $service_url = 'https://api.hubapi.com/oauth/v1/token';
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'grant_type' => 'refresh_token',
            'client_id' => env('HUBSPOT_APP_CLIENT_ID'),
            'client_secret' => env('HUBSPOT_APP_CLIENT_SECRET'),
            'redirect_uri' => env('HUBSPOT_APP_REDIRECT_URI'),
            'refresh_token' => $hubspot_app_refresh_token
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post_data));

        $curl_response = json_decode(curl_exec($curl), true);

        \Redis::set('hubspot_app_access_token', $curl_response['access_token']);
        \Redis::set('hubspot_app_refresh_token', $curl_response['refresh_token']);

        $this->info('Sucess: HubSpot access token refreshed' );
    }
}
