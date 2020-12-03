<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $maxtries = 5;
    public $view;
    public $subject;
    public $data = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $sub, $data)
    {
        $this->view = $view;
        $this->subject = $sub;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view)->with($this->data);
    }
}
