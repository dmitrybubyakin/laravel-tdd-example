<?php

namespace App\Notifications\Admin;

use App\Models\ContactFormSubmission;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactFormSubmission extends Notification
{
    private ContactFormSubmission $form;

    public function __construct(ContactFormSubmission $form)
    {
        $this->form = $form;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = new MailMessage;

        $message->subject("There is a new form submission by {$this->form->name}!");

        return $message;
    }
}
