<?php

namespace App\Http\Requests\api\v1\community;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class addCommunityMemberRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'user_ids' => 'required|string',
            'community_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_ids.required' => 'User IDs are required',
            'user_ids.string' => 'User IDs must be a string',
            'community_id.required' => 'Community ID is required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
