<?php

namespace App\Http\Requests\api\v1\admin\trophy;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class addTrophyRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The trophy name is required.',
            'name.string' => 'The trophy name must be a valid string.',
            'name.max' => 'The trophy name must not exceed 255 characters.',

            'description.string' => 'The description must be a valid string.',

            'image.required' => 'The trophy image is required.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
