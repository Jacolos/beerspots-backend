<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeerPriceChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $beer;
    private $oldPrice;
    private $newPrice;

    public function __construct($beer, $oldPrice, $newPrice)
    {
        $this->beer = $beer;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Zmiana ceny piwa')
            ->line('Cena piwa "' . $this->beer->name . '" w punkcie "' . $this->beer->beerSpot->name . '" została zmieniona.')
            ->line('Stara cena: ' . $this->oldPrice . ' zł')
            ->line('Nowa cena: ' . $this->newPrice . ' zł')
            ->action('Zobacz szczegóły', url('/beer-spots/' . $this->beer->beerSpot->id));
    }
}