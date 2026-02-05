<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class switchRoleRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'role' => 'required|string|in:user,matchmaker,mentor',
        ];
    }
    //custom messages
    public function messages()
    {
        return [
            'role.required' => 'Role is required',
            'role.string' => 'Role must be a string',
            'role.in' => 'Role must be one of the following: user, matchmaker, mentor',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
