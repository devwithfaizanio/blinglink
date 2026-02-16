<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'phone',
        'price',
        'is_approved',
        'approved_at',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
