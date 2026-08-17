<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trophy extends Model
{
    //
    protected $fillable = [
        'name',
        'image',
        'description',
    ];

    public function getImageAttribute($image): ?string
    {
        return $image ? asset('images/trophies/'.$image) : null;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'trophy_id');
    }
}
