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
        return [
            'id'     => $this->id,
            'user_id'     => $this->user_id,
            'community_id'     => $this->community_id,
            'message'     => $this->message,
            'attachment'  => $this->attachment,
            'is_read'     => $this->is_read,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
