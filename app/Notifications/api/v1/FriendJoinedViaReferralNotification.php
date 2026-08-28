<?php

namespace App\Notifications\api\v1;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FriendJoinedViaReferralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $friend
    ) {}

    public function via(object $notifiable): array
    {
        return [FirebaseChannel::class, 'database'];
    }

    public function getData(): array
    {
        return [
            'type' => 'friend_joined_referral',
            'friend_id' => $this->friend->id,
        ];
    }

    public function toFirebase($notifiable): array
    {
        return [
            'title' => 'Your Friend Joined BlingLink! 🎉',
            'body' => "{$this->friend->f_name} just joined BlingLink using your invite code. You are now connected!",
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Your Friend Joined BlingLink! 🎉',
            'body' => "{$this->friend->f_name} just joined BlingLink using your invite code. You are now connected!",
            'type' => 'friend_joined_referral',
            'friend_id' => $this->friend->id,
        ];
    }
}
