<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchmakerProfiles extends Model
{
    //
    protected $fillable = [
        'user_id',
        'phone',
        'city',
        'experience_years',
        'matchmaking_type',
        'preferred_age_min',
        'preferred_age_max',
        'preferred_gender',
        'coverage_area',
        'total_matches',
        'success_story',
        'id_document',
        'is_verified',
        'is_approved',
        'approved_at',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
