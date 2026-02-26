<?php

namespace App\Events;

use App\Models\MatchMakerChat;
use App\Models\MentorChat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class MatchmakerMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(MatchMakerChat $message)
    {
        $this->message = $message->load('fromUser'); // adjust relation name if different
    }

    public function broadcastOn()
    {
        // Private channel between two users
        return new PrivateChannel('matchmaker-chat.' . $this->message->from_id . '.' . $this->message->to_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->toArray(),
        ];
    }
}
