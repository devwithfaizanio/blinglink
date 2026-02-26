<?php

namespace App\Events;

use App\Models\ConnectionChat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class ConnectionMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(ConnectionChat $message)
    {
        $this->message = $message->load('fromUser'); // adjust relation name if different
    }

    public function broadcastOn()
    {
        // Private channel between two users
        return new PrivateChannel('connection.' . $this->message->from_id . '.' . $this->message->to_id);
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
