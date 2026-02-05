<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class CheckUserAvailabilityRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email is already taken',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm Password is required',
            'password_confirmation.same' => 'Password and Confirm Password must be same',

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
