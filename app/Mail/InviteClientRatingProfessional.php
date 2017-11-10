<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteClientRatingProfessional extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    private $client;
    private $professional;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $professional)
    {
        //
        $this->client = $client;
        $this->professional = $professional;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [];
        $data['align'] = 'center';
        $data['messageTitle'] = $this->client->full_name . ' sua avaliação é muito importante!';
        $data['messageOne'] = 'O '. $this->professional->full_name .' está listado na plataforma iSaudavel e gostaria muito de sua avaliação para o perfil profissional dele. Sua ajuda conta muito!';

        $data['messageTwo'] = 'Acesse o link abaixo e avalie o trabalho e atendimento de ' . $this->professional->full_name. '.';

        $data['button_link'] = 'https://app.isaudavel.com/#/avaliar/profissionais/' . $this->professional->slug;
        $data['button_name'] = 'Avaliar';

        $data['messageSubject'] = 'Avaliação';

        return $this->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.')
            ->to($this->client->email, $this->client->full_name)
            ->view('emails.standart-with-btn')
            ->subject($data['messageSubject'])
            ->with(['data' => $data]);
    }
}
