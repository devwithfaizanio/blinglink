<?php

namespace App\Http\Resources\api\v1\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getUserResource extends JsonResource
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
            'f_name' => $this->f_name,
            'email' => $this->email,
            'role' => $this->role,
            'is_approved' => $this->is_approved ? 'true' : 'false',
            'age' => $this->age,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'profession' => $this->profession,
            'company' => $this->company,
            'dubai_location' => $this->dubai_location,
            'height' => $this->height,
            'education_level' => $this->education_level,
            'family' => $this->family,

            'lifestyle_preference' => $this->lifestyle_preference,
            'your_interest' => $this->your_interest,
            'languages' => $this->languages,

            'bio' => $this->bio,
            'linkedin_profile' => $this->linkedin_profile,
            'emirate_id' => $this->emirate_id,
            'profile_image' => $this->profile_image,

            'subscription_plan' => $this->subscription_plan,
            'recommendation_count' => $this->recommendation_count,
            'recommendation_reset_at' => $this->recommendation_reset_at,
            'customer_id' => $this->customer_id,
            'hasTrophy' => $this->trophy_id ? true : false,
            'trophyImage' => $this->trophy_id ? $this->trophy->image : null,
            'trophy_reward' => $this->trophy_reward,
        ];
    }
}
