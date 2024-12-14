<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Frontend URL configuration
     */
    protected $frontendUrl = 'https://beerspots.pl';

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🍺 Witaj w świecie BeerSpots!')
            ->greeting('Cześć ' . $notifiable->name . '! 👋')
            ->line('Cieszymy się, że dołączyłeś do społeczności miłośników dobrego piwa.')
            ->line('W BeerSpots możesz:')
            ->line('• 📍 Odkrywać najlepsze punkty z piwem w okolicy')
            ->line('• ⭐ Dzielić się swoimi opiniami i doświadczeniami')
            ->line('• 🔍 Sprawdzać aktualne ceny i dostępność')
            ->line('• 📱 Być na bieżąco z nowymi miejscami')
            ->action('Rozpocznij eksplorację', $this->frontendUrl)
            ->line('Masz pytania? Odpowiedz na tego maila, a nasz zespół chętnie pomoże!')
            ->salutation("Na zdrowie! 🍻\nZespół BeerSpots");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => 'Witaj w BeerSpots!',
            'action_url' => $this->frontendUrl
        ];
    }
}