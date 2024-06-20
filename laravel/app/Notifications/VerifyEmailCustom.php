<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends VerifyEmailBase
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject( __( 'emails.verify_title' ))
            ->markdown('emails.register', ['url' => $url]);
    }
}