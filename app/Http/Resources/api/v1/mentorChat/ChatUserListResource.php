<?php

namespace App\Http\Resources\api\v1\mentorChat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'f_name' => $this->f_name,
            'profile_image' => $this->profile_image,
            'last_message' => $this->mentorChatLastMessage($this->id)->message,
            'last_message_time' => $this->mentorChatLastMessage($this->id)->created_at->diffForHumans()
        ];
    }
}
