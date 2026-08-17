<?php

namespace App\Http\Requests\api\v1\admin\trophy;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class assignTrophyRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'trophy_id' => 'required|exists:trophies,id',
            'reward' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user field is required.',
            'user_id.exists' => 'The user field does not exist.',
            'trophy_id.required' => 'The trophy field is required.',
            'trophy_id.exists' => 'The trophy field does not exist.',
            'reward.required' => 'The reward field is required.',
            'reward.numeric' => 'The reward field must be a number.',


        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
