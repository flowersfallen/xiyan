<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Regist extends Mailable
{
    use Queueable, SerializesModels;

    public $activeUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($activeUrl)
    {
        $this->activeUrl = $activeUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.regist');
    }
}
