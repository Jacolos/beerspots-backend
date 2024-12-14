<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBeerSpotNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $beerSpot;

    public function __construct($beerSpot)
    {
        $this->beerSpot = $beerSpot;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nowy punkt sprzedaży do weryfikacji')
            ->line('Dodano nowy punkt sprzedaży: ' . $this->beerSpot->name)
            ->line('Adres: ' . $this->beerSpot->address)
            ->action('Sprawdź szczegóły', route('admin.beer-spots.show', $this->beerSpot))
            ->line('Wymaga weryfikacji przez administratora.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_beer_spot',
            'beer_spot_id' => $this->beerSpot->id,
            'message' => 'Nowy punkt sprzedaży wymaga weryfikacji: ' . $this->beerSpot->name,
            'action_url' => route('admin.beer-spots.show', $this->beerSpot)
        ];
    }
}