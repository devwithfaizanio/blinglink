<?php

namespace App\Http\Requests\api\v1\admin\chats;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getMatchmakerChatRequest extends FormRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            'matchmaker_id' => 'required|exists:connection_to_matchmakers,matchmaker_id',
            'user_id' => 'required|exists:connection_to_matchmakers,user_id',
            'limit' => 'nullable|integer',
            'page' => 'nullable|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'matchmaker_id.required' => 'Matchmaker ID is required.',
            'matchmaker_id.exists' => 'The selected matchmaker connection does not exist.',

            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'The selected user connection does not exist.',


            'limit.integer' => 'Limit must be an integer.',
            'page.integer' => 'Page must be an integer.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
