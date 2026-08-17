<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'detail',
        'image',
        'status',
        'published_at',
        'created_by',
    ];

    public function getImageAttribute($image): ?string
    {
        return $image ? asset('images/blogs/'.$image) : null;
    }
}
