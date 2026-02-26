<?php

namespace App\Http\Resources\api\v1\payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class myMentorResource extends JsonResource
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
            'mentor_id' => $this->mentor_id,
            'mentor_name' => $this->mentor->f_name,
            'mentor_email' => $this->mentor->email,
            'amount' => $this->paymentHistory->amount,
            'connected_at' => $this->created_at->diffForHumans(),
        ];
    }
}
