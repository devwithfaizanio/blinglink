<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'creator_id',
        'is_active',
        'status'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    public function messages()
    {
        return $this->hasMany(CommunityMessage::class);
    }

    // Check if user is creator
    public function isCreator($userId)
    {
        return $this->creator_id == $userId;
    }

    // Check if user is member
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    public function getImageAttribute($image): ?string
    {
//        $imageList = glob(public_path('random_images/avatar/*'));
//        $randomImage_Avatar = $imageList[array_rand($imageList)];
//        return $image ? asset('images/profile/'.$image) : asset('random_images/avatar/'.basename($randomImage_Avatar));

        return $image ? asset('images/communities/'.$image) : null;
    }
}
