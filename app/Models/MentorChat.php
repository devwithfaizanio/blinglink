<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorChat extends Model
{
    //
    protected $fillable = [
        'from_id',
        'to_id',
        'message',
        'attachment',
        'is_read',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
    public function getAttachmentAttribute($value)
    {
        if ($value) {
            return asset('images/mentor-chat/message/' . $value);
        }
        return null;
    }
}
