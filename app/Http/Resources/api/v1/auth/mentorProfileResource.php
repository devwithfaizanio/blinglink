<?php

namespace App\Http\Resources\api\v1\auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class mentorProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'price' => $this->price,
        ];
    }

}
