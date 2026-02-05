<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date_time',
        'location',
        'venue',
        'event_type',
        'ticket_price',
        'event_image',
        'status',
    ];
    protected $casts = [
        'event_date_time' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];
    //getevent_imageAttribute to return full url
    public function getEventImageAttribute($value)
    {
        if ($value) {
            return asset('images/events/' . $value);
        }
        return null;
    }

    // creator
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // attendees list
    public function attendees()
    {
        return $this->hasMany(EventAttendee::class);
    }
}
