<?php

namespace App\Http\Requests\api\v1\admin\promocode;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class storePromoCodeRequest extends FormRequest
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Promo code is required.',
            'code.string' => 'Promo code must be a string.',
            'code.max' => 'Promo code may not exceed 50 characters.',
            'code.unique' => 'This promo code already exists.',

            'max_uses.integer' => 'Max uses must be an integer.',
            'max_uses.min' => 'Max uses must be at least 1.',

            'start_date.date' => 'Start date must be a valid date.',

            'expires_at.date' => 'Expires at must be a valid date.',
            'expires_at.after_or_equal' => 'Expires at date must be after or equal to start date.',

            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
