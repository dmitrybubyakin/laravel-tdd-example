<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormWillBeProcessedSoon extends Notification
{
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = new MailMessage;

        $message->subject('The form will be processed soon. Thank you!');

        return $message;
    }
}
