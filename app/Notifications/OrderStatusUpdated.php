<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
   
    public $order;
    public $oldStatus;
    public $newStatus;
    public $message;

    public function __construct($order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} Status Update")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your order #{$this->order->id} status has been updated.")
            ->line("Previous status: {$this->oldStatus}")
            ->line("New status: {$this->newStatus}")
            ->action('View Order', url('/orders/' . $this->order->id))
            ->line('Thank you for choosing Cysuites!');
    }
}
