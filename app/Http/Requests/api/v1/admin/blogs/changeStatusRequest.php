<?php

namespace App\Http\Requests\api\v1\admin\blogs;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class changeStatusRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'blog_id' => 'required|exists:blogs,id',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'blog_id.required' => 'Blog ID is required.',
            'blog_id.exists' => 'The selected blog does not exist.',

            'status.required' => 'Status is required.',
            'status.in' => 'Status must be draft, published, or archived.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
