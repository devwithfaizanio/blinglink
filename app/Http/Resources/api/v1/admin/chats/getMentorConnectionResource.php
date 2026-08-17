<?php

namespace App\Http\Resources\api\v1\admin\chats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getMentorConnectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mentor_id' => $this->mentor_id,
            'user_id' => $this->user_id,
            'mentor_name' => $this->mentor->f_name,
            'user_name' => $this->user->f_name,
            'mentor_image' => $this->mentor->profile_image,
            'user_image' => $this->user->profile_image,
        ];
    }
}
