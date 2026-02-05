<?php

namespace App\Http\Requests\api\v1\community;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class createCommunityRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Community name is required',
            'name.string' => 'Community name must be a string',
            'name.max' => 'Community name must not exceed 255 characters',
            'description.string' => 'Description must be a string',
            'image.image' => 'Image must be a valid image file',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'Image size must not exceed 2048 kilobytes',

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
