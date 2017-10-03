<?php

namespace App\Listeners;

use App\Events\ClientActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncrementClientTotalXp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ClientActivity  $event
     * @return void
     */
    public function handle(ClientActivity $event)
    {
        $client = $event->client;
        $points = $event->points;

        $client->increment('total_xp', $points);
    }
}
