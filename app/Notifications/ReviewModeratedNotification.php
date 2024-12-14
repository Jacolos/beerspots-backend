<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewModeratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $review;
    private $status;

    public function __construct($review, $status)
    {
        $this->review = $review;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $status = $this->status === 'approved' ? 'zatwierdzona' : 'odrzucona';
        
        return (new MailMessage)
            ->subject('Status Twojej recenzji został zaktualizowany')
            ->line('Twoja recenzja dla punktu "' . $this->review->beerSpot->name . '" została ' . $status . '.')
            ->line('Dziękujemy za Twoją aktywność w społeczności BeerSpot!');
    }
}