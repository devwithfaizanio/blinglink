<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

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
        'profile_image',

        'subscription_plan',
        'recommendation_count',
        'recommendation_reset_at',
        'customer_id',
        'status',
        'trophy_id',
        'trophy_reward',
        'promo_code_id',
        'is_approved',
        'referral_code',
        'referred_by_id'
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
            'recommendation_reset_at' => 'datetime',
        ];
    }

    protected $attributes = [
        'subscription_plan'    => 'free',  // default
        'recommendation_count' => 0,
    ];

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




    public function getRecommendationLimit(): int
    {
        return match($this->subscription_plan) {
            'free'    => 10,
            'basic'   => 20,
            'premium' => 50,
            'vip'     => 999999,
            default   => 10,
        };
    }

    public function hasReachedRecommendationLimit(): bool
    {
        return $this->recommendation_count >= $this->getRecommendationLimit();
    }

    public function resetRecommendationIfNewMonth(): void
    {
        if (
            is_null($this->recommendation_reset_at) ||
            $this->recommendation_reset_at->month !== now()->month ||
            $this->recommendation_reset_at->year  !== now()->year
        ) {
            $this->update([
                'recommendation_count'    => 0,
                'recommendation_reset_at' => now(),
            ]);
        }
    }

    /**
     * @return Customer|null
     *@throws ApiErrorException
     */
    public function createOrGetStripeCustomer(): ?Customer
    {
        if(isset($this->customer_id)){
            try {
                $customer =  Customer::retrieve($this->customer_id);

                return $customer->isDeleted() ? $this->createCustomer() : $customer;

            } catch (ApiErrorException $e) {
                return $this->createCustomer();
            }
        }else {
            return $this->createCustomer();
        }
    }

    /**
     * @return Customer
     * @throws ApiErrorException
     */
    private function createCustomer(): Customer
    {
        try {
            $customer = Customer::search([
                'query' => 'email~' . $this->email,
            ])->first();
            if(filled($customer) && !$customer->isDeleted()){

                $this->customer_id = $customer->id;
                $this->save();
                return $customer;
            }
        } catch (ApiErrorException $e) {
            Log::error($e->getMessage(), $e->getTrace());
        }

        $customer = Customer::create([
            'email' => $this->email,
            'description' => 'Customer for ' . $this->email,
            'shipping' => [
                'address' => [
                    'city' => $this->nationality,
                ],
                'name' => $this->f_name,
            ]
        ]);

        $this->customer_id = $customer->id;
        $this->save();
        return $customer;
    }

    public function trophy()
    {
        return $this->belongsTo(Trophy::class, 'trophy_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = static::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode(): string
    {
        do {
            $code = 'BLING-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    public function referralInvites()
    {
        return $this->hasMany(UserReferral::class, 'referrer_id');
    }
}
