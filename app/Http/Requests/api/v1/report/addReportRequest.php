<?php

namespace App\Http\Requests\api\v1\report;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class addReportRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'to_user_id' => 'required|exists:users,id',
            'reason_to_report' => 'required|string',
        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'to_user_id.required' => 'To user id is required',
            'to_user_id.exists' => 'To user id is invalid',
            'reason_to_report.required' => 'Reason to report is required',
            'reason_to_report.string' => 'Reason to report must be string',

        ];

    }


    public function authorize(): bool
    {
        return true;
    }
}
