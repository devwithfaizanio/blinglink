<?php

namespace App\Http\Resources\api\v1\mentorChat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mediaType = null;

        if ($this->attachment) {
            $extension = strtolower(pathinfo($this->attachment, PATHINFO_EXTENSION));

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $mediaType = 'image';
            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
                $mediaType = 'video';
            } elseif (in_array($extension, ['mp3', 'wav', 'ogg', 'm4a'])) {
                $mediaType = 'voice';
            } else {
                $mediaType = 'file';
            }
        }
        return [
            'id' => $this->id,
            'from_id' => $this->from_id,
            'to_id' => (int)$this->to_id,
            'from_userName' => $this->fromUser->f_name,
            'to_userName' => $this->toUser->f_name,
            'message' => $this->message,
            'attachment' => $this->attachment,
            'media_type'   => $mediaType,
            'message_at' => $this->created_at->diffForHumans(),
            'message_updated_at' => $this->updated_at->diffForHumans(),

        ];
    }
}
