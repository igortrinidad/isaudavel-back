<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OracleNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $notification_data
     */
    public $notification_data;

    /**
     * Create a new event instance.
     *
     * @param $notification_data
     */
    public function __construct($notification_data)
    {
        $this->notification_data = $notification_data;
    }
}
