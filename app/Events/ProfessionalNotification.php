<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProfessionalNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $professional_id
     */
    public $professional_id;
    /**
     * @var $notification_data
     */
    public $notification_data;

    /**
     * Create a new event instance.
     *
     * @param $professional_id
     * @param $notification_data
     */
    public function __construct($professional_id, $notification_data)
    {
        $this->professional_id = $professional_id;
        $this->notification_data = $notification_data;
    }
}
