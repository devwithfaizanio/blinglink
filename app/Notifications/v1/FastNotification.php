<?php

namespace App\Notifications\v1;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\v1\FirebaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(

        private readonly string $title,
        private readonly string $message,
        private readonly string $notificationType,

    )
    {
        //
    }


    public function via(object $notifiable): array
    {
        return [FirebaseChannel::class, 'database'];
    }
    public function getData(): array
    {
        return [
            'type' => 'fast',
        ];
    }

    public function toFirebase($notifiable): array
    {
        return [
            'body' => $this->message,
            'title' => $this->title,
        ];
    }
    public function toDatabase($notifiable): array
    {

        return [
            'title' => $this->title,
            'body' => $this->message,
            'type' => 'fast'
        ];
    }
}
