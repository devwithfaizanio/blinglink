<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityMessage extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'community_id',
        'user_id',
        'message',
        'attachment',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
    public function getAttachmentAttribute($value)
    {
        if ($value) {
            return asset('images/communities/message/' . $value);
        }
        return null;
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
