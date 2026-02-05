<?php

namespace App\Http\Resources\api\v1\community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getMemberListResource extends JsonResource
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
            'description' => $this->description,
            'image' => $this->image ? asset($this->image) : null,
            'members' => $this->when($this->relationLoaded('members'), function() {
                return $this->members->map(function($member) {
                    return [
                        'id' => $member->id,
                        'f_name' => $member->f_name,
                        'email' => $member->email,
                        'pivot' => [
                            'community_id' => $member->pivot->community_id,
                            'user_id' => $member->pivot->user_id,
                            'joined_at' => $member->pivot->joined_at,
                        ],
                    ];
                });
            }),
            'members_count' => $this->when($this->relationLoaded('members'), function() {
                return $this->members->count();
            }),
        ];

    }

}
