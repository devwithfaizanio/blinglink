<?php

namespace App\Http\Requests\api\v1\community;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class sndMsgRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'community_id' => 'required|integer|exists:communities,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'

        ];
    }

    public function messages()
    {
        return [
            'community_id.required' => 'Community ID is required',
            'community_id.integer' => 'Community ID must be an integer',
            'community_id.exists' => 'Community not found',
            'message.string' => 'Message must be a string',
            'attachment.image' => 'Image must be a valid image file',
            'attachment.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif',
            'attachment.max' => 'Image size must not exceed 2048 kilobytes',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
