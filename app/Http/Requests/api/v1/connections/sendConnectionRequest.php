<?php

namespace App\Http\Requests\api\v1\connections;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class sendConnectionRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'requested_id' => 'required|exists:users,id',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'requested_id.required' => 'Requested user id is required',
            'requested_id.exists' => 'Requested user does not exist',
        ];

    }


    public function authorize(): bool
    {
        return true;
    }
}
