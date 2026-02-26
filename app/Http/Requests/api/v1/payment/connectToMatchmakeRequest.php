<?php

namespace App\Http\Requests\api\v1\payment;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class connectToMatchmakeRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'matchmaker_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'matchmaker');
                }),
            ],
            'amount' => 'required|numeric|min:0',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'matchmaker_id.required' => 'Matchmaker ID is required.',
            'matchmaker_id.exists' => 'The selected matchmaker does not exist or is not a matchmaker.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount must be at least 0.',
        ];

    }
}
