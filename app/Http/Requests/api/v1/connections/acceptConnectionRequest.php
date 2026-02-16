<?php

namespace App\Http\Requests\api\v1\connections;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class acceptConnectionRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'connection_id' => 'required|exists:connections,id',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'connection_id.required' => 'Connection id is required',
            'connection_id.exists' => 'Connection does not exist',
        ];

    }


    public function authorize(): bool
    {
        return true;
    }
}
