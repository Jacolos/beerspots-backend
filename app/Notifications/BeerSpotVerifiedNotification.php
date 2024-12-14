<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeerSpotVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $beerSpot;

    public function __construct($beerSpot)
    {
        $this->beerSpot = $beerSpot;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Punkt sprzedaży zweryfikowany')
            ->line('Twój punkt sprzedaży "' . $this->beerSpot->name . '" został zweryfikowany.')
            ->line('Jest teraz widoczny dla wszystkich użytkowników.')
            ->action('Zobacz swój punkt', url('/beer-spots/' . $this->beerSpot->id));
    }
}