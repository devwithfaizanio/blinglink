<?php

namespace App\Http\Resources\api\v1\events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'description' => $this->description,
            'event_date_time' => $this->event_date_time,
            'location' => $this->location,
            'venue' => $this->venue,
            'event_type' => $this->event_type,
            'ticket_price' => $this->ticket_price,
            'status' => $this->status,
            'event_image' => $this->event_image,
            'isMine' => $this->user_id === auth()->id(),
            'created_at' => $this->created_at,
            'joined_count' => $this->attendees()->where('status', 'going')->count(),
            'cancelled_count' => $this->attendees()->where('status', 'cancelled')->count(),
        ];
    }
}
