<?php

namespace App\Http\Requests\api\v1\events;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getEventsRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'limit' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'status' => 'nullable|string|in:all,active,cancelled,completed',
            'isMine' => 'sometimes|boolean',
        ];
    }
    //custom messages
    public function messages()
    {
        return [
            'status.in' => 'The status field must be one of the following values: all, active, cancelled, completed.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
