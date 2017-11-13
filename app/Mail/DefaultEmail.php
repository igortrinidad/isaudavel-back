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
     * @var
     */
    private $horizon_tags;

    /**
     * Create a new message instance.
     *
     * @param $data
     * @param $horizon_tags
     */
    public function __construct($data, $horizon_tags = [])
    {
        $this->data = $data;
        $this->horizon_tags = $horizon_tags;
    }

    public function tags()
    {
        return $this->horizon_tags;
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
