<?php

namespace App\Http\Requests\api\v1\report;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateReportRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'report_id' => 'required|exists:reports,id',
            'status' => 'required|in:resolved,dismissed',
            'admin_quote' => 'required|string',

        ];
    }

    //custom messages
    public function messages()
    {
        return [
            'report_id.required' => 'Report ID is required.',
            'report_id.exists' => 'Report ID does not exist.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of resolved, dismissed.',
            'admin_quote.required' => 'Admin Quote is required.',
            'admin_quote.string' => 'Admin Quote must be a string.',

        ];

    }


    public function authorize(): bool
    {
        return true;
    }
}
