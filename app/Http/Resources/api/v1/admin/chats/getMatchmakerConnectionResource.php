<?php

namespace App\Http\Resources\api\v1\admin\chats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getMatchmakerConnectionResource extends JsonResource
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
            'matchmaker_id' => $this->matchmaker_id,
            'user_id' => $this->user_id,
            'matchmaker_name' => $this->matchmaker->f_name,
            'user_name' => $this->user->f_name,
            'matchmaker_image' => $this->matchmaker->profile_image,
            'user_image' => $this->user->profile_image,
        ];
    }
}
