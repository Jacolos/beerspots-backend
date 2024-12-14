<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nowa recenzja wymaga moderacji')
            ->line('Dodano nową recenzję dla punktu: ' . $this->review->beerSpot->name)
            ->line('Ocena: ' . $this->review->rating . '/5')
            ->line('Autor: ' . $this->review->user->name)
            ->action('Sprawdź recenzję', route('admin.reviews.show', $this->review));
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_review',
            'review_id' => $this->review->id,
            'message' => 'Nowa recenzja wymaga moderacji dla: ' . $this->review->beerSpot->name,
            'action_url' => route('admin.reviews.show', $this->review)
        ];
    }
}