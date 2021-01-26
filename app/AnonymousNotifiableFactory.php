<?php

namespace App;

use Illuminate\Notifications\AnonymousNotifiable;

class AnonymousNotifiableFactory
{
    public static function email(string $email): AnonymousNotifiable
    {
        $notifiable = new class extends AnonymousNotifiable {
            public function getKey(): string
            {
                return $this->routes['mail'];
            }
        };

        $notifiable->route('mail', $email);

        return $notifiable;
    }
}
