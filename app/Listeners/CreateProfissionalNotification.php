<?php

namespace App\Listeners;

use App\Events\ProfessionalNotification as ProfessionalNotificationEvent;
use App\Models\Professional;
use App\Models\ProfessionalNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class CreateProfissionalNotification
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
     * @param  ProfessionalNotificationEvent  $event
     * @return void
     */
    public function handle(ProfessionalNotificationEvent $event)
    {
        $professional = Professional::find($event->professional_id);

        $data = $event->notification_data;


        /*
        * New company
        */
        if($data['type'] == 'new_company'){
            $payload = $data['payload'];

            $notification_data = [
                'professional_id' => $professional->id,
                'title' => 'Solicitação de empresa',
                'content' => 'O profissional ' .$payload['user_full_name'] . ' acabou de adicionar você na empresa ' .$payload['company_name'].'.',
                'button_label' => 'Ir para empresas',
                'button_action' => '/profissional/dashboard?tab=companies'
            ];
        }

        /*
        * Professional calendar settings change
        */
        if($data['type'] == 'professional_calendar_settings_change'){
            $payload = $data['payload'];

            $notification_data = [
                'professional_id' => $professional->id,
                'title' => 'Alteração de agenda',
                'content' => 'A empresa '. $payload->company->name . ' alterou as configurações da sua agenda de ' .$payload->category->name .'.',
                'button_label' => 'Ir para agenda',
                'button_action' => '/profissional/dashboard?tab=workdays'
            ];
        }

        /*
        * New rating
        */
        if($data['type'] == 'new_rating'){
            $rating = $data['payload'];

            $notification_data = [
                'professional_id' => $professional->id,
                'title' => 'Nova avaliação',
                'content' => $rating->client->full_name . ' avaliou você com ' . $rating->rating . ($rating->rating > 1 ? ' estrelas' : ' estrela'). ', veja o que ele escreveu em sua avaliação.',
                'button_label' => 'Ver avaliações',
                'button_action' => '/profissional/dashboard?tab=ratings'
            ];
        }

        /*
         * Company accept solicitation
         */
        if($data['type'] == 'company_accept'){
            $company = $data['payload'];

            $notification_data = [
                'professional_id' => $professional->id,
                'title' => 'Solicitação aprovada',
                'content' => $company->name . ' aceitou a solicitação enviada.' ,
                'button_label' => 'Ir para empresas',
                'button_action' => '/profissional/dashboard?tab=companies'
            ];
        }

        /*
        * Create and send the push notification
        */
        $notification = ProfessionalNotification::create($notification_data);

        if($professional->fcm_token_mobile){
            $this->sendPushNotification($professional->fcm_token_mobile, $notification_data);
        }

        if($professional->fcm_token_browser){
            $this->sendPushNotification($professional->fcm_token_browser, $notification_data, false);
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