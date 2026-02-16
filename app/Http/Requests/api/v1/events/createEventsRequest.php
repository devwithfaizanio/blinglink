<?php

namespace App\Http\Requests\api\v1\events;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class createEventsRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date_time' => 'required|date|after_or_equal:now',
            'location' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'ticket_price' => 'required|numeric|min:0',
            'max_attendees' => 'required|numeric|min:0',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'title.required' => 'Event title is required.',
            'description.required' => 'Event description is required.',
            'event_date_time.required' => 'Event date and time is required.',
            'event_date_time.after_or_equal' => 'Event date and time must be today or future.',

            'location.required' => 'Event location is required.',
            'venue.required' => 'Event venue is required.',
            'event_type.required' => 'Event type is required.',

            'ticket_price.required' => 'Ticket price is required.',
            'ticket_price.numeric' => 'Ticket price must be a number.',
            'ticket_price.min' => 'Ticket price cannot be negative.',

            'max_attendees.required' => 'Max attendees is required.',
            'max_attendees.numeric' => 'Max attendees must be a number.',
            'max_attendees.min' => 'Max attendees cannot be negative.',

            'event_image.image' => 'Event image must be a valid image file.',
            'event_image.mimes' => 'Event image must be a file of type: jpeg, png, jpg, gif.',
            'event_image.max' => 'Event image size must not exceed 2048 kilobytes.',
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
