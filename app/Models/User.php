<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'f_name',
        'email',
        'password',
        'fcm_token',

        'age',
        'gender',
        'nationality',
        'profession',
        'company',
        'dubai_location',
        'height',
        'education_level',
        'family',

        'lifestyle_preference',
        'your_interest',
        'languages',

        'bio',
        'linkedin_profile',
        'emirate_id',
        'profile_image'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'lifestyle_preference' => 'array',
            'your_interest' => 'array',
        ];
    }

    public function getEmirateIdAttribute($image): ?string
    {
        return $image ? asset('images/user/emirate_id/'.$image) : null;
    }
    public function getProfileImageAttribute($image): ?string
    {
        return $image ? asset('images/user/profile_image/'.$image) : asset('default_images/profile.png');
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    public function createdCommunities()
    {
        return $this->hasMany(Community::class, 'creator_id');
    }
    public function matchmakerProfile()
    {
        return $this->hasOne(MatchmakerProfiles::class);
    }
    public function mentorProfile()
    {
        return $this->hasOne(MentorProfile::class);
    }


    public function createdEvents()
    {
        return $this->hasMany(Event::class);
    }

    public function joinedEvents()
    {
        return $this->hasMany(EventAttendee::class);
    }

    public function sentConnections()
    {
        return $this->hasMany(Connection::class, 'requester_id');
    }

    public function receivedConnections()
    {
        return $this->hasMany(Connection::class, 'requested_id');
    }
    //alreadyPendingRequest function to check if there is already a pending connection request between the authenticated user and another user
    public function alreadyPendingRequest($otherUserId)
    {        $pendingRequest = Connection::where(function ($query) use ($otherUserId) {
            $query->where('requester_id', auth()->id())
                ->where('requested_id', $otherUserId);
        })->orWhere(function ($query) use ($otherUserId) {
            $query->where('requester_id', $otherUserId)
                ->where('requested_id', auth()->id());
        })->where('status', 'pending')->first();
        return $pendingRequest;
    }

    public function getLifestylePreferenceTextAttribute(): string
    {
        $value = $this->lifestyle_preference;

        // If it's already an array
        if (is_array($value)) {
            return !empty($value) ? implode(', ', $value) : 'N/A';
        }

        // If it's a JSON string
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return !empty($decoded) ? implode(', ', $decoded) : 'N/A';
            }

            return $value; // if it's normal string
        }

        return 'N/A';
    }

    public function getInterestTextAttribute(): string
    {
        $value = $this->your_interest;

        if (is_array($value)) {
            return !empty($value) ? implode(', ', $value) : 'N/A';
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return !empty($decoded) ? implode(', ', $decoded) : 'N/A';
            }

            return $value;
        }

        return 'N/A';
    }

    public function connectionChatLastMessage($userId)
    {

        $lastMessge =  ConnectionChat::query()
            ->where('from_id', $userId)
            ->where('to_id', request()->user()->id)
            ->orWhere(function($query) use ($userId) {
                $query->where('to_id', $userId)
                    ->where('from_id', request()->user()->id);
            })
            ->latest()
            ->first();
        return $lastMessge;
    }
    public function mentorChatLastMessage($userId)
    {

        $lastMessge =  MentorChat::query()
            ->where('from_id', $userId)
            ->where('to_id', request()->user()->id)
            ->orWhere(function($query) use ($userId) {
                $query->where('to_id', $userId)
                    ->where('from_id', request()->user()->id);
            })
            ->latest()
            ->first();
        return $lastMessge;
    }

    public function matchmakerChatLastMessage($userId)
    {
        $lastMessge =  MatchMakerChat::query()
            ->where('from_id', $userId)
            ->where('to_id', request()->user()->id)
            ->orWhere(function($query) use ($userId) {
                $query->where('to_id', $userId)
                    ->where('from_id', request()->user()->id);
            })
            ->latest()
            ->first();
        return $lastMessge;
    }


}
