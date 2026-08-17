<?php

namespace App\Http\Resources\api\v1\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getMatchmakerResource extends JsonResource
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
            'matchmaker_id' => $this->matchmaker_id,
            'matchmaker_name' => $this->matchmaker->f_name,
            'matchmaker_email' => $this->matchmaker->email,
            'matchmaker_profile' => $this->matchmaker->profile_image,
            'user_name' => $this->user->f_name,
            'user_email' => $this->user->email,
            'user_profile' => $this->user->profile_image,
            'amount' => $this->paymentHistory->amount,
            'connected_at' => $this->created_at->diffForHumans(),
        ];
    }
}
