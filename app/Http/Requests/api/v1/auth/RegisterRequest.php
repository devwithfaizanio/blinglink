<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'f_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
            'fcm_token' => 'required|string',

            'age' => 'required|integer',
            'gender' => 'required|string',
            'nationality' => 'required|string',
            'profession' => 'required|string',
            'company' => 'required|string',
            'dubai_location' => 'required|string',
            'height' => 'required|string',
            'education_level' => 'required|string',
            'family' => 'required|string',

            'lifestyle_preference' => 'required|string',
            'bio' => 'required|string',
            'your_interest' => 'required|string',
            'languages' => 'required|string',


            'linkedin_profile' => 'required|url',
            'emirate_id' => 'required|image|mimes:jpeg,png,jpg,gif',

            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ];
    }


    public function messages()
    {
        return [
            'f_name.required' => 'Name is required',

            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email is already taken',

            'password.required' => 'Password is required',

            'password_confirmation.required' => 'Confirm Password is required',
            'password_confirmation.same' => 'Password and Confirm Password must be same',

            'fcm_token.required' => 'FCM Token is required',

            'age.required' => 'Age is required',
            'age.integer' => 'Age must be a number',

            'gender.required' => 'Gender is required',
            'nationality.required' => 'Nationality is required',
            'profession.required' => 'Profession is required',
            'company.required' => 'Company is required',
            'dubai_location.required' => 'Dubai location is required',
            'height.required' => 'Height is required',
            'education_level.required' => 'Education level is required',
            'family.required' => 'Family information is required',

            'lifestyle_preference.required' => 'Lifestyle preference is required',
            'lifestyle_preference.array' => 'Lifestyle preference must be a valid list',

            'bio.required' => 'Bio is required',

            'your_interest.required' => 'Your interest is required',

            'languages.required' => 'Languages are required',

            'linkedin_profile.required' => 'LinkedIn profile is required',
            'linkedin_profile.url' => 'LinkedIn profile must be a valid URL',

            'emirate_id.required' => 'Emirate ID is required',
            'emirate_id.image' => 'Emirate ID must be an image',
            'emirate_id.mimes' => 'Emirate ID must be a file of type: jpeg, png, jpg, gif',

            'profile_image.required' => 'Profile image is required',
            'profile_image.image' => 'Profile image must be an image',
            'profile_image.mimes' => 'Profile image must be a file of type: jpeg, png, jpg, gif',
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
