<?php

namespace App\Listeners;

use App\Events\ClientNotification as ClientNotificationEvent;
use App\Models\Client;
use App\Models\ClientNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

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
        $client = Client::find( $event->client_id);

        $data = $event->notification_data;


        if($data['type'] == 'new_company'){

        }

        if($data['type'] == 'new_trainning'){

            $trainning = $data['payload'];

            $notification_data = [
                'client_id' => $client->id,
                'title' => 'Treinamento adicionado',
                'content' => $trainning->from->full_name .' adicionou um novo treinamento para você.',
                'button_label' => 'Visualizar treinamentos',
                'button_action' => '/cliente/dashboard/' . $client->id . '?tab=trainnings'
            ];

            $notification = ClientNotification::create($notification_data);

            if($client->fcm_token_mobile){
                $this->sendPushNotification($client->fcm_token_mobile, $notification_data);
            }

            if($client->fcm_token_browser){
                $this->sendPushNotification($client->fcm_token_browser, $notification_data);
            }
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

    public function sendPushNotification($token, $payload){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle($payload['title'])
            ->setBody($payload['content'])
            ->setSound('default')
            ->setIcon('https://app.isaudavel.com/static/assets/img/icons/icon_g.png')
            ->setClickAction('FCM_PLUGIN_ACTIVITY');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['icon' => 'https://app.isaudavel.com/static/assets/img/icons/icon_g.png']);
        foreach($payload as $key => $value){
            $dataBuilder->addData([$key => $value]);
        }

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = \FCM::sendTo($token, $option, $notification, $data);
    }
}
