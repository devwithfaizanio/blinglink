<?php

namespace App\Http\Requests\api\v1\admin;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class accountStatusRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|in:normal,warn,suspended,ban',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The user_id is not valid.',
            'status.in' => 'Status must be normal,warn,suspended, Or ban.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
