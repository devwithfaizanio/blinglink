<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
            'fcm_token' => 'required|string'
        ];
    }
    //custom messages
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.exists' => 'Email is does not exist',
            'password.required' => 'Password is required',
            'fcm_token.required' => 'FCM Token is required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
