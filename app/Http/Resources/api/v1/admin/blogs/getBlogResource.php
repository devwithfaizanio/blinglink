<?php

namespace App\Http\Resources\api\v1\admin\blogs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getBlogResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'detail' => $this->detail,
            'image' => $this->image,
            'status' => $this->status,
            'published_at' => $this->published_at,
        ];
    }
}
