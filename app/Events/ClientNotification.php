<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ClientNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $client_id
     */
    public $client_id;
    /**
     * @var $notification_data
     */
    public $notification_data;

    /**
     * Create a new event instance.
     *
     * @param $client_id
     * @param $notification_data
     */
    public function __construct($client_id, $notification_data)
    {
        $this->client_id = $client_id;
        $this->notification_data = $notification_data;
    }
}
