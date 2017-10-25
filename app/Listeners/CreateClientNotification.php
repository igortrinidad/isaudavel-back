<?php

namespace App\Listeners;

use App\Events\ClientNotification as ClientNotificationEvent;
use App\Models\ClientNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateClientNotification
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
     * @param  ClientNotificationEvent  $event
     * @return void
     */
    public function handle(ClientNotificationEvent $event)
    {
        $client_id = $event->client_id;

        $data = $event->notification_data;


        if($data['type'] == 'new_company'){

        }

        if($data['type'] == 'new_trainning'){

            $trainning = $data['payload'];

            ClientNotification::create([
                'client_id' => $client_id,
                'title' => 'Treinamento adicionado',
                'content' => $trainning->from->full_name .' adicionou um novo treinamento para você.',
                'button_label' => 'Visualizar treinamentos',
                'button_action' => '/cliente/dashboard/' . $client_id . '?tab=trainnings',
            ]);
        }

        if($data['type'] == 'new_diet'){

        }

        if($data['type'] == 'new_schedule'){

        }

        if($data['type'] == 'new_reschedule'){

        }

        if($data['type'] == 'new_single_schedule'){

        }
    }
}
