<?php

namespace App\Http\Requests\api\v1\auth;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class mentorProfileRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'phone' => 'nullable|string',
            'price' => 'required|string',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'price.required' => 'Price is required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
