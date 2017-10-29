<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CompanyNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $company_id
     */
    public $company_id;
    /**
     * @var $notification_data
     */
    public $notification_data;

    /**
     * Create a new event instance.
     *
     * @param $company_id
     * @param $notification_data
     */
    public function __construct($company_id, $notification_data)
    {
        $this->company_id = $company_id;
        $this->notification_data = $notification_data;
    }
}
