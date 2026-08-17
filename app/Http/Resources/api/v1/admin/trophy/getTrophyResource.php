<?php

namespace App\Http\Resources\api\v1\admin\trophy;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getTrophyResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image,
        ];
    }
}
