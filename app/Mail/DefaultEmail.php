<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DefaultEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    private $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct( $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.')
            ->view('emails.standart-with-btn')
            ->subject($this->data['messageSubject'])
            ->with(['data' => $this->data]);
    }
}
