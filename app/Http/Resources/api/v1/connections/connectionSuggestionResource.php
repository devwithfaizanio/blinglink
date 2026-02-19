<?php

namespace App\Http\Resources\api\v1\connections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class connectionSuggestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = [
            'id' => $this->id,
            'f_name' => $this->f_name,
            'email' => $this->email,
            'role' => $this->role,
            'bio' => $this->bio,
            'profile_image' => $this->profile_image,
            'alreadyPendingRequest' => $this->alreadyPendingRequest($this->id) ? true : false,
        ];

        if($this->alreadyPendingRequest($this->id) != null){
            $data['connection_id'] = $this->alreadyPendingRequest($this->id)['id'];
        }

        return $data;
    }
}
