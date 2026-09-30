<?php

namespace App\Notifications;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TripCompletedEscrowNotification extends Notification
{
    use Queueable;

    protected $trip;

    /**
     * Create a new notification instance.
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
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
            'trip_id' => $this->trip->id,
            'load_title' => $this->trip->cargo->title ?? 'Muatan Cargo',
            'driver_name' => $this->trip->driver->name,
            'message' => 'Driver '.$this->trip->driver->name.' telah menyelesaikan perjalanan. Mohon review POD dan segera cairkan Cargo Fee (Escrow) kepada driver.',
            'url' => route('admin.bidding'),
        ];
    }
}
