<?php

namespace App\Listeners;

use App\Events\CompanyNotification as CompanyNotificationEvent;
use App\Models\Company;
use App\Models\CompanyNotification;
use App\Models\ProfessionalNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class CreateCompanyNotification
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
     * @param CompanyNotificationEvent $event
     * @return void
     */
    public function handle(CompanyNotificationEvent $event)
    {
        $company = Company::with('professionals')->find( $event->company_id);

        $data = $event->notification_data;

        /*
         * New client
         */
        if($data['type'] == 'new_client'){
            $client = $data['payload']['client'];
            $company = $data['payload']['company'];

            $notification_data = [
                'title' => 'Novo cliente',
                'content' => $client->full_name . ' enviou uma solicitação para ser cliente da empresa '.$company->name.' e está aguardando aprovação.' ,
                'button_label' => 'Ir para clientes',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '?tab=clients'
            ];
        }

        /*
         * Client accept solicitation
         */
        if($data['type'] == 'client_accept'){
            $client = $data['payload']['client'];
            $company = $data['payload']['company'];

            $notification_data = [
                'title' => 'Solicitação aprovada',
                'content' => $client->full_name . ' aceitou a solicitação enviada.' ,
                'button_label' => 'Ir para clientes',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '?tab=clients'
            ];
        }

        /*
         * Client remove client
         */
        if($data['type'] == 'client_remove_company'){
            $client = $data['payload']['client'];
            $company = $data['payload']['company'];

            $notification_data = [
                'title' => 'Cliente removido',
                'content' => $client->full_name . ' removeu a empresa '.$company->name.' de sua lista de empresas.' ,
            ];
        }

        /* ON RESCHEDULE */
        if($data['type'] == 'reschedule'){

            $schedule = $data['payload']['schedule'];

            $notification_data = [
                'title' => 'Remarcação de agendamento',
                'content' => $schedule->client->full_name .' remarcou um agendamento de '. $schedule->category->name.  ' para ' . $schedule->date . ' ' . $schedule->time. '.',
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '/schedule/' . $schedule->id
            ];
        }


        /*
         * Cancel schedule
         */
        if($data['type'] == 'cancel_schedule'){
            $schedule = $data['payload'];

            $notification_data = [
                'title' => 'Cancelamento de horário',
                'content' => $schedule->client->full_name .' cancelou o agendamento de '. $schedule->category->name. ' marcado para ' . $schedule->date . ' ' . $schedule->time,
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '/schedule/' . $schedule->id
            ];
        }

        /*
        * Reschedule a single schedule
        */
        if($data['type'] == 'single_reschedule'){
            $single_schedule = $data['payload']['single_schedule'];

            $notification_data = [
                'title' => 'Remarcação de agendamento',
                'content' => $single_schedule->client->full_name . ' remarcou um agendamento de '. $schedule->category->name.  ' para ' . $single_schedule->date . ' ' . $single_schedule->time. '.',
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '/single-schedule/' . $single_schedule->id
            ];
        }

        /*
         * Cancel single
         */
        if($data['type'] == 'cancel_single_schedule'){
            $single_schedule = $data['payload'];

            $notification_data = [
                'title' => 'Cancelamento de horário',
                'content' => $single_schedule->client->full_name .' cancelou o agendamento de '. $single_schedule->category->name. ' marcado para ' . $single_schedule->date . ' ' . $single_schedule->time,
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '/single-schedule/' . $single_schedule->id
            ];
        }

        /*
         * Create and send the push notification
         */
        foreach($company->professionals as $professional){

            array_set($notification_data, 'professional_id', $professional->id);

            $notification = ProfessionalNotification::create($notification_data);

            if($professional->fcm_token_mobile){
                $this->sendPushNotification($professional->fcm_token_mobile, $notification_data);
            }

            if($professional->fcm_token_browser){
                $this->sendPushNotification($professional->fcm_token_browser, $notification_data, false);
            }
            
        }

    }

    public function sendPushNotification($token, $payload, $is_mobile = true){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $base_url = \App::environment('local') ? 'http://localhost:38080/#' : 'https://app.isaudavel.com/#';

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle($payload['title'])
            ->setBody($payload['content'])
            ->setSound('default')
            ->setIcon('https://app.isaudavel.com/static/assets/img/icons/icon_g.png');

        //On mobile set click action to FCM PLUGIN
        if ($is_mobile) {
            $notificationBuilder->setClickAction('FCM_PLUGIN_ACTIVITY');
        }

        //On browser set click action to desired url
        if (!$is_mobile && isset($payload['button_action'])) {
            $notificationBuilder->setClickAction($base_url . $payload['button_action']);
        }

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
