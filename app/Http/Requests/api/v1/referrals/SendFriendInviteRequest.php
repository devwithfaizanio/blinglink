<?php

namespace App\Http\Requests\api\v1\referrals;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendFriendInviteRequest extends FormRequest
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            'email' => 'required|email',
            'email' => 'nullable|email',
            'message' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
//            'email.required' => 'Friend email address is required',
            'email.required' => 'Friend email address is required',
            'email.email' => 'Please enter a valid email address',
            'message.max' => 'Invite message cannot exceed 500 characters',
        ];
    }
}
