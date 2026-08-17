<?php

namespace App\Http\Resources\api\v1\auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class profileResource extends JsonResource
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
            'age' => $this->age,
            'profile_image' => $this->profile_image,
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
            'bio' => $this->bio,
            'linkedin_profile' => $this->linkedin_profile,
            'emirate_id' => $this->emirate_id,
            'is_approved' => (bool) $this->is_approved,
            'hasMatchmakerProfile' => $this->matchmakerProfile ? true : false,
            'hasMentorProfile' => $this->mentorProfile ? true : false,
            'trophy_id' => $this->trophy_id ?? null,
            'promo_code_id' => $this->promo_code_id ?? null,
            'promo_code' => $this->promoCode ? $this->promoCode->code : null,
            'subscription_plan' => $this->subscription_plan ?? null,
        ];

        if ($this->role === 'matchmaker') {
            $data['matchmakerApprovalStatusByAdmin'] = (bool) \App\Models\MatchmakerProfiles::where('user_id', $this->id)
                ->value('is_approved');
            $data['matchmakerProfile'] = new matchMakerProfileResource($this->matchmakerProfile);
        }
        if ($this->role === 'mentor') {
            $data['mentorApprovalStatusByAdmin'] = (bool) \App\Models\MentorProfile::where('user_id', $this->id)
                ->value('is_approved');
            $data['mentorProfile'] = new mentorProfileResource($this->mentorProfile);
        }

        return $data;
    }
}
