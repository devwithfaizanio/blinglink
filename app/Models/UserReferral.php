<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'friend_email',
        'friend_phone',
        'status',
        'registered_user_id',
    ];

    public function referrer(): BelongsTo
    {
        return $table = $this->belongsTo(User::class, 'referrer_id');
    }

    public function registeredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }
}
