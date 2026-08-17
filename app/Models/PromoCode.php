<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $table = 'promo_codes';

    protected $fillable = [
        'code',
        'max_uses',
        'uses_count',
        'start_date',
        'expires_at',
        'status',
        'created_by',
    ];

    protected $casts = [
        'max_uses' => 'integer',
        'uses_count' => 'integer',
        'start_date' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'promo_code_id');
    }
}
