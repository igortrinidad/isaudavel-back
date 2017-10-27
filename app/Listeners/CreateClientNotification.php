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

        /*
        * New Training
        */
        if($data['type'] == 'new_trainning'){

            $trainning = $data['payload'];

            $notification_data = [
                'client_id' => $client->id,
                'title' => 'Treinamento adicionado',
                'content' => $trainning->from->full_name .' adicionou um novo treinamento para você.',
                'button_label' => 'Visualizar treinamentos',
                'button_action' => '/cliente/dashboard/' . $client->id . '?tab=trainnings'
            ];
        }

        if($data['type'] == 'new_diet'){

        }

        if($data['type'] == 'new_schedule'){

        }

        /*
         * Reschedule a plan schedule
         */
        if($data['type'] == 'reschedule'){
            $schedule = $data['payload']['schedule'];
            $old_schedule = $data['payload']['old_schedule'];

            $notification_data = [
                'client_id' => $client->id,
                'title' => 'Alteração de horário',
                'content' => 'O usuário '. $schedule->reschedule_by . ' acabou de alterar seu horário de ' .$schedule->category->name . ' marcado anteriormente para ' . $old_schedule->date . ' ' . $old_schedule->time . '. O novo horário foi definido para ' .$schedule->date . ' ' . $schedule->time,
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/cliente/dashboard/calendar/'.urlencode($schedule->date).'/schedule/'.$schedule->id
            ];
        }

        /*
         * Reschedule a single schedule
         */
        if($data['type'] == 'single_reschedule'){
            $single_schedule = $data['payload']['single_schedule'];
            $old_single_schedule = $data['payload']['old_single_schedule'];

            $notification_data = [
                'client_id' => $client->id,
                'title' => 'Alteração de horário',
                'content' => 'O usuário '. $single_schedule->reschedule_by . ' acabou de alterar seu horário de ' .$single_schedule->category->name . ' marcado anteriormente para ' . $old_single_schedule->date . ' ' . $old_single_schedule->time . '. O novo horário foi definido para ' .$single_schedule->date . ' ' . $single_schedule->time,
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/cliente/dashboard/calendar/'.urlencode($single_schedule->date).'/single-schedule/'.$single_schedule->id
            ];
        }

        /*
         * New single schedule
         */
        if($data['type'] == 'new_single_schedule'){
            $single_schedule = $data['payload'];

            $notification_data = [
                'client_id' => $client->id,
                'title' => 'Novo agendamento',
                'content' => 'O usuário '. \Auth::user()->full_name . ' adicionou um novo agendamento de ' .$single_schedule->category->name . ' para você. O novo horário foi definido para '. $single_schedule->date . ' ' . $single_schedule->time,
                'button_label' => ' Visualizar agendamento',
                'button_action' => '/cliente/dashboard/calendar/'.urlencode($single_schedule->date).'/single-schedule/'.$single_schedule->id,
            ];
        }

        /*
         * Create and send the push notification
         */

        $notification = ClientNotification::create($notification_data);

        if($client->fcm_token_mobile){
            $this->sendPushNotification($client->fcm_token_mobile, $notification_data);
        }

        if($client->fcm_token_browser){
            $this->sendPushNotification($client->fcm_token_browser, $notification_data);
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
