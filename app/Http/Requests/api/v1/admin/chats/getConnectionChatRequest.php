<?php

namespace App\Http\Requests\api\v1\admin\chats;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getConnectionChatRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'requester_id' => 'required|exists:connections,requester_id',
            'requested_id' => 'required|exists:connections,requested_id',
            'limit' => 'nullable|integer',
            'page' => 'nullable|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'requester_id.required' => 'Requester ID is required.',
            'requester_id.exists' => 'The selected requester does not exist in connections.',

            'requested_id.required' => 'Requested ID is required.',
            'requested_id.exists' => 'The selected requested user does not exist in connections.',


            'limit.integer' => 'Limit must be an integer.',
            'page.integer' => 'Page must be an integer.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
