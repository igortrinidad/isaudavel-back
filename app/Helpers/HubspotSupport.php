<?php

if (! function_exists('hubspot_support')) {
    function hubspot_support($endpoint, $params)
    {
        $client = new \SevenShores\Hubspot\Http\Client(['key' => env('HUBSPOT_API_KEY')]);

        $queryString = build_query_string($params);

        return $client->request('get', $endpoint, [], $queryString);
    }
}
