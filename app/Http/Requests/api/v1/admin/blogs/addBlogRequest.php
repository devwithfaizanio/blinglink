<?php

namespace App\Http\Requests\api\v1\admin\blogs;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class addBlogRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'detail' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title may not be greater than 255 characters.',

            'detail.required' => 'detail is required.',
            'detail.string' => 'detail must be a valid string.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be jpg, jpeg, png, or webp format.',
            'image.max' => 'Image size may not exceed 2MB.',

            'status.required' => 'Status is required.',
            'status.in' => 'Status must be draft, published, or archived.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
