<?php

namespace App\Http\Requests\api\v1\admin;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getUserRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'role' => 'nullable|in:user,matchmaker,mentor',
            'verified' => 'nullable|boolean',
            'is_trophy' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Role must be user, matchmaker, or mentor.',
            'verified.boolean' => 'Verified must be 0 or 1.',
            'is_trophy.boolean' => 'is_trophy must be 0 or 1.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
