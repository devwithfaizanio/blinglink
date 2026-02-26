<?php

namespace App\Http\Requests\api\v1\connectionChat;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class sndMsgRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'to_id' => 'required|integer|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi,mp3,wav,m4a,aac,ogg|max:51200'

        ];
    }

    public function messages()
    {
        return [
            'to_id.required' => 'Recipient ID is required.',
            'to_id.integer' => 'Recipient ID must be an integer.',
            'to_id.exists' => 'Recipient ID does not exist.',
            'message.string' => 'Message must be a string.',
            'attachment.file' => 'Attachment must be a file.',
            'attachment.mimes' => 'Attachment must be a file of type: jpeg, png, jpg, gif, pdf, mp4, mov, avi, mp3, wav, m4a, aac, ogg.',
            'attachment.max' => 'Attachment size must not exceed 50MB.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
