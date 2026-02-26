<?php

namespace App\Http\Resources\api\v1\community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class msgResource extends JsonResource
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
            'id'     => $this->id,
            'user_id'     => $this->user_id,
            'community_id'     => $this->community_id,
            'message'     => $this->message,
            'attachment'  => $this->attachment,
            'media_type'   => $mediaType,
            'is_read'     => $this->is_read,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
