<?php

namespace App\Listeners;

use App\Events\CompanyNotification as CompanyNotificationEvent;
use App\Models\Company;
use App\Models\CompanyNotification;
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
     * @param  ClientNotificationEvent  $event
     * @return void
     */
    public function handle(CompanyNotificationEvent $event)
    {
        $company = Company::with('professionals')->find( $event->company_id);

        $data = $event->notification_data;

        /* ON RESCHEDULE */
        if($data['type'] == 'new_reschedule'){

            $reschedule = $data['payload'];

            $notification_data = [
                'company_id' => $company->id,
                'title' => 'Remarcação de agendamento',
                'content' => $reschedule->client->full_name .' remarcou um agendamento para ' . $reschedule->date . ' ' . $reschedule->date '.',
                'button_label' => 'Visualizar agendamento',
                'button_action' => '/dashboard/empresas/mostrar/' . $company->id . '/schedule/' . $reschedule->id
            ];
        }

        /*
         * Create and send the push notification
         */

        $notification = CompanyNotification::create($notification_data);

        foreach($company->professionals as $professional){

            if($professional->fcm_token_mobile){
                $this->sendPushNotification($professional->fcm_token_mobile, $notification_data);
            }

            if($professional->fcm_token_browser){
                $this->sendPushNotification($professional->fcm_token_browser, $notification_data);
            }
            
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
