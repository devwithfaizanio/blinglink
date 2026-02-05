<?php

namespace App\Http\Requests\api\v1\events;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateEventsRequest extends FormRequest
{
    use FailedValidation;
    use FailedValidation;

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'event_date_time' => 'nullable|date|after_or_equal:now',

            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',

            'event_type' => 'nullable|string|max:255',

            'ticket_price' => 'nullable|numeric|min:0',

            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'status' => 'nullable|string|in:active,cancelled,completed',
        ];
    }


    //custom messages
    public function messages()
    {
        return [

            'event_date_time.after_or_equal' => 'Event date and time must be today or future.',

            'ticket_price.numeric' => 'Ticket price must be a number.',
            'ticket_price.min' => 'Ticket price cannot be negative.',

            'event_image.image' => 'Event image must be a valid image file.',
            'event_image.mimes' => 'Event image must be a file of type: jpeg, png, jpg, gif.',
            'event_image.max' => 'Event image size must not exceed 2048 kilobytes.',

            'status.in' => 'The status field must be one of the following values: active, cancelled, completed.',
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
