<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Promotion;

class PromotionCreated extends Notification
{
    use Queueable;

    public $promotion;

    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New Promotion: {$this->promotion->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new promotion is available: {$this->promotion->description}")
            ->line("Valid from {$this->promotion->start_date} to {$this->promotion->end_date}")
            ->action('View Promotion', url('/promotions'))
            ->line('Don’t miss out!');
    }
}
