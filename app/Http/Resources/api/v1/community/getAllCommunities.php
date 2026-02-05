<?php

namespace App\Http\Resources\api\v1\community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getAllCommunities extends JsonResource
{
    protected $showMembers;

    public function __construct($resource, $showMembers = null)
    {
        parent::__construct($resource);
        $this->showMembers = $showMembers;
    }
    public function toArray(Request $request): array
    {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image ? asset($this->image) : null,
            'creator_id' => $this->creator_id,
            'is_active' => $this->is_active,
            'isMineCommunity' => auth()->check() && $this->creator_id === auth()->id(),
            'creator' => $this->when($this->relationLoaded('creator'), function() {
                return [
                    'id' => $this->creator->id,
                    'f_name' => $this->creator->f_name,
                    'email' => $this->creator->email,
                ];
            }),
            'members_count' => $this->when($this->relationLoaded('members'), function() {
                return $this->members->count();
            }),
            'last_message' => $this->when($this->relationLoaded('messages'), function() {
                $lastMessage = $this->messages->sortByDesc('created_at')->first();
                if ($lastMessage) {
                    return [
                        'id' => $lastMessage->id,
                        'message' => $lastMessage->message,
                    ];
                }
                return null;
            }),
        ];


        if ($this->showMembers) {
            $data['members'] = $this->when($this->relationLoaded('members'), function() {
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
            });
        }

        return $data;

    }
}
