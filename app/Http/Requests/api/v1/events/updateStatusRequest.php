<?php

namespace App\Http\Requests\api\v1\events;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateStatusRequest extends FormRequest
{
    use FailedValidation;
    use FailedValidation;

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,approve,rejected,completed',

        ];
    }


    //custom messages
    public function messages()
    {
        return [

            'status.required' => 'Status is required.',
            'status.in' => 'The status field must be one of the following values: pending, approve, rejected, completed.',
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
