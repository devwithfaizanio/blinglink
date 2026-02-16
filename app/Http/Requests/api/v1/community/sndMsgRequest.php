<?php

namespace App\Http\Requests\api\v1\community;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class sndMsgRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'community_id' => 'required|integer|exists:communities,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi,mp3,wav,m4a,aac,ogg|max:51200'

        ];
    }

    public function messages()
    {
        return [
            'community_id.required' => 'Community ID is required',
            'community_id.integer' => 'Community ID must be an integer',
            'community_id.exists' => 'Community not found',
            'message.string' => 'Message must be a string',
            'attachment.file' => 'Attachment must be a file',
            'attachment.mimes' => 'Attachment must be a file of type: jpeg, png, jpg, gif, pdf, mp4, mov, avi, mp3, wav, m4a, aac, ogg',
            'attachment.max' => 'Attachment must not be greater than 50MB',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
