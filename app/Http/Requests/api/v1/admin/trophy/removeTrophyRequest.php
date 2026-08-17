<?php

namespace App\Http\Requests\api\v1\admin\trophy;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class removeTrophyRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user field is required.',
            'user_id.exists' => 'The user field does not exist.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
