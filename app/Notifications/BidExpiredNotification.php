<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BidExpiredNotification extends Notification
{
    use Queueable;

    protected $load;

    protected $messageText;

    /**
     * Create a new notification instance.
     */
    public function __construct($load, $messageText)
    {
        $this->load = $load;
        $this->messageText = $messageText;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'load_id' => $this->load->id,
            'title' => 'Batas Waktu Bidding Habis',
            'message' => $this->messageText,
        ];
    }
}
