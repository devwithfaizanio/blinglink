<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateProfileRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'f_name' => 'nullable|string',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string',
            'nationality' => 'nullable|string',
            'profession' => 'nullable|string',
            'company' => 'nullable|string',
            'dubai_location' => 'nullable|string',
            'height' => 'nullable|string',
            'education_level' => 'nullable|string',
            'family' => 'nullable|string',

            'lifestyle_preference' => 'nullable|string',
            'bio' => 'nullable|string',
            'your_interest' => 'nullable|string',
            'languages' => 'nullable|string',

            'linkedin_profile' => 'nullable|url',

            'emirate_id' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }


    public function messages()
    {
        return [

            'linkedin_profile.url' => 'LinkedIn profile must be a valid URL',

            'emirate_id.image' => 'Emirate ID must be an image',
            'emirate_id.mimes' => 'Emirate ID must be a file of type: jpeg, png, jpg, gif',

            'profile_image.image' => 'Profile image must be an image',
            'profile_image.mimes' => 'Profile image must be a file of type: jpeg, png, jpg, gif',
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
