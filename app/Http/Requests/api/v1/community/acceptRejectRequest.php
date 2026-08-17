<?php

namespace App\Http\Requests\api\v1\community;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class acceptRejectRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'community_id' => 'required|integer|exists:communities,id',
            'status' => 'required|in:accepted,declined',

        ];
    }

    public function messages()
    {
        return [
            'community_id.required' => 'Community ID is required.',
            'community_id.integer'  => 'Community ID must be a valid integer.',
            'community_id.exists'   => 'The selected community does not exist.',

            'status.required'       => 'Status is required.',
            'status.in'             => 'Status must be either accepted or declined.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
