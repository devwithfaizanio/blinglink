<?php

namespace App\Http\Resources\api\v1\admin\promocode;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
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
            'code' => $this->code,
            'max_uses' => $this->max_uses,
            'uses_count' => $this->uses_count,
            'start_date' => $this->start_date?->toDateTimeString(),
            'expires_at' => $this->expires_at?->toDateTimeString(),
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
