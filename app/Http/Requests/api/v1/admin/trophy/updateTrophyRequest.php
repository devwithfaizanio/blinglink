<?php

namespace App\Http\Requests\api\v1\admin\trophy;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateTrophyRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'trophy_id' => 'required|integer|exists:trophies,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ];
    }

    public function messages(): array
    {
        return [
            'trophy_id.required' => 'The trophy id field is required.',
            'trophy_id.integer' => 'The trophy id must be an integer.',
            'trophy_id.exists' => 'The trophy id must be an existing.',

            'name.string' => 'The trophy name must be a valid string.',
            'name.max' => 'The trophy name must not exceed 255 characters.',

            'description.string' => 'The description must be a valid string.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
