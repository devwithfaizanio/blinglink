<?php

namespace App\Http\Requests\api\v1\report;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getReportRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'limit' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'status' => 'nullable|in:all,pending,resolved,dismissed',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'status.in' => 'Status must be all pending, resolved, dismissed',

        ];

    }


    public function authorize(): bool
    {
        return true;
    }
}
