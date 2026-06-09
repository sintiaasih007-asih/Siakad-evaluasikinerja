<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendSetupPasswordMail extends Mailable
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Setup Password - SIAKAD SMP Dianto Landong')
                    ->view('emails.setup-password');
    }
}