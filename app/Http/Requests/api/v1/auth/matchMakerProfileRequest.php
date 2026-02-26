<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class matchMakerProfileRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'city' => 'required|string',
            'price' => 'required|string',

            'experience_years' => 'nullable|integer|min:0',

            'matchmaking_type' => 'required|string',

            'preferred_age_min' => 'required|integer|min:18',
            'preferred_age_max' => 'required|integer|gte:preferred_age_min',

            'preferred_gender' => 'required|in:male,female,both',

            'coverage_area' => 'required|string',

            'success_story' => 'required|string',
            'id_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'total_matches' => 'required|integer|min:0',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'phone.required' => 'Phone is required',
            'city.required' => 'City is required',
            'price.required' => 'Price is required',
            'experience_years.integer' => 'Experience years must be an integer',
            'experience_years.min' => 'Experience years must be at least 0',
            'matchmaking_type.required' => 'Matchmaking type is required',
            'preferred_age_min.required' => 'Preferred minimum age is required',
            'preferred_age_min.integer' => 'Preferred minimum age must be an integer',
            'preferred_age_min.min' => 'Preferred minimum age must be at least 18',
            'preferred_age_max.required' => 'Preferred maximum age is required',
            'preferred_age_max.integer' => 'Preferred maximum age must be an integer',
            'preferred_age_max.gte' => 'Preferred maximum age must be greater than or equal to preferred minimum age',
            'preferred_gender.required' => 'Preferred gender is required',
            'coverage_area.required' => 'Coverage area is required',
            'success_story.required' => 'Success Story area is required',
            'id_document.file' => 'ID document must be a file',
            'id_document.mimes' => 'ID document must be a file of type: jpg, jpeg, png, pdf',
            'id_document.max' => 'ID document must not be greater than 5MB',
            'total_matches.required' => 'Total matches is required',
            'total_matches.integer' => 'Total matches must be an integer',
            'total_matches.min' => 'Total matches must be at least 0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
