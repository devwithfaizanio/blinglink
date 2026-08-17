<?php

namespace App\Http\Requests\api\v1\admin\blogs;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getBlogRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'status' => 'nullable|in:draft,published,archived',
            'is_mine' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status must be draft, published, or archived.',
            'is_mine.boolean' => 'is_mine must be 0 or 1.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
