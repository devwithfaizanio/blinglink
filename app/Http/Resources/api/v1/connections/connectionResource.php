<?php

namespace App\Http\Resources\api\v1\connections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class connectionResource extends JsonResource
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
            'requester_id' => $this->requester_id,
            'requested_id' => $this->requested_id,
            'status' => $this->status,
            'requested_at' => $this->created_at->diffForHumans(),
            'requester' => [
                'id' => $this->requester->id,
                'f_name' => $this->requester->f_name,
                'email' => $this->requester->email,
                'bio' => $this->requester->bio,
                'profile_image' => $this->requester->profile_image,
            ],
            'requested' => [
                'id' => $this->requested->id,
                'f_name' => $this->requested->f_name,
                'email' => $this->requested->email,
                'bio' => $this->requested->bio,
                'profile_image' => $this->requested->profile_image,
            ],
        ];
    }
}
