<?php

namespace App\Http\Resources\api\v1\auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            'trophy_id' => $this->trophy_id,
        ];
        if($this->role == 'matchmaker'){
            $data['matchmaker_profile'] = matchMakerProfileResource::make($this->matchmakerProfile);
        }elseif($this->role == 'mentor'){
            $data['mentor_profile'] = mentorProfileResource::make($this->mentorProfile);
        }

        return $data;

    }
}
