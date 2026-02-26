<?php

namespace App\Http\Requests\api\v1\payment;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class connectToMentorRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'mentor_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'mentor');
                }),
            ],
            'amount' => 'required|numeric|min:0',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'mentor_id.required' => 'Mentor ID is required.',
            'mentor_id.exists' => 'The selected mentor does not exist or is not a mentor.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount must be at least 0.',
        ];

    }
}
