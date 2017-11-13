<?php

namespace App\Listeners;


use App\Events\OracleNotification as OracleNotificationEvent;
use App\Models\OracleNotification;
use App\Models\OracleUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class CreateOracleNotification
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
     * @param  OracleNotificationEvent  $event
     * @return void
     */
    public function handle(OracleNotificationEvent $event)
    {
        $data = $event->notification_data;
        $only_toast = false;

        /*
        * New professional
        */
        if($data['type'] == 'new_professional'){
            $professional = $data['payload'];

            $notification_data = [
                'title' => 'Cadastro de profissional',
                'content' => $professional->full_name .' acabou de se cadastrar no iSaudavel',
                'button_label' => 'Ver profissional',
                'button_action' => '/oracle/dashboard/profissionais/exibir/'.$professional->id,
                'type' => $data['type']
            ];
        }

        /*
        * New Company
        */
        if($data['type'] == 'new_company'){
            $company = $data['payload'];

            $notification_data = [
                'title' => 'Cadastro de empresa',
                'content' => $company->name .' acabou de ser cadastrada pelo usuário '. $company->owner->full_name .'.',
                'button_label' => 'Ver empresa',
                'button_action' => '/oracle/dashboard/empresas/editar/'.$company->id,
                'type' => $data['type']
            ];
        }

        /*
        * New contact added on Hubspot
        */
        if($data['type'] == 'hubspot_contact_creation'){
            $contact = $data['payload'];


            $notification_data = [
                'title' => 'HubSpot',
                'content' => $contact['name'].' '.$contact['last_name']. ' foi adicionado no HubSpot.',
                'type' => $data['type'],
                'only_sales_dashboard' => true
            ];

            $only_toast = true;
        }

       /*
       * Contact removed on Hubspot
       */
        if($data['type'] == 'hubspot_contact_deletion'){
            $event = $data['payload'];

            $notification_data = [
                'title' => 'HubSpot',
                'content' => 'Um contato foi removido do HubSpot.',
                'type' => $data['type'],
                'only_sales_dashboard' => true
            ];

            $only_toast = true;
        }

       /*
       * Plan update
       */
        if($data['type'] == 'plan_update'){
            $professional = $data['payload'];

            $notification_data = [
                'title' => 'Atualização de plano',
                'content' => ($professional->full_name) .' atualizou seu plano para '.( $professional->is_paid ? 'PLUS' : 'FREE'),
                'button_label' => 'Ver profissional',
                'button_action' => '/oracle/dashboard/profissionais/exibir/'.$professional->id,
                'type' => $data['type']
            ];
        }


        /*
       * Create and send the push notification
       */

        foreach(OracleUser::all() as $oracle_user){

            array_set($notification_data, 'oracle_user_id', $oracle_user->id);

            //Create the notification on database only if required
            if(!$only_toast){
                $notification = OracleNotification::create($notification_data);
            }

            if($oracle_user->fcm_token_mobile){
                $this->sendPushNotification($oracle_user->fcm_token_mobile, $notification_data);
            }

            if($oracle_user->fcm_token_browser){
                $this->sendPushNotification($oracle_user->fcm_token_browser, $notification_data, false);
            }

        }

    }

    public function sendPushNotification($token, $payload, $is_mobile = true){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $base_url = \App::environment('local') ? 'http://localhost:8000' : 'https://isaudavel.com/';

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
