<?php

namespace App\Http\Resources\api\v1\admin\chats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getConnectionResource extends JsonResource
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
            'requester_name' => $this->requester->f_name,
            'requested_name' => $this->requested->f_name,
            'requester_image' => $this->requester->profile_image,
            'requested_image' => $this->requested->profile_image,
        ];
    }
}
