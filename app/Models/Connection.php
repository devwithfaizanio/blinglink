<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    //
    protected $fillable = [
        'requester_id',
        'requested_id',
        'status',
    ];
    //relationships
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    //relationships
    public function requested()
    {
        return $this->belongsTo(User::class, 'requested_id');
    }
}
