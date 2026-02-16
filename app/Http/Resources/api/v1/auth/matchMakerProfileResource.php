<?php

namespace App\Http\Resources\api\v1\auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class matchMakerProfileResource extends JsonResource
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
            'city' => $this->city,
            'experience_years' => $this->experience_years,
            'matchmaking_type' => $this->matchmaking_type ? json_decode($this->matchmaking_type) : null,
            'preferred_age_min' => $this->preferred_age_min,
            'preferred_age_max' => $this->preferred_age_max,
            'preferred_gender' => $this->preferred_gender,
            'coverage_area' => $this->coverage_area ? json_decode($this->coverage_area) : null,
            'total_matches' => $this->total_matches,
            'success_story' => $this->success_story,
            'id_document' => $this->id_document,
            'is_verified' => (bool) $this->is_verified,
            'is_approved' => (bool) $this->is_approved,
            'approved_at' => $this->approved_at,
            'admin_note' => $this->admin_note,
        ];
    }

}
