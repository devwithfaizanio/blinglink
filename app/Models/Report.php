<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'reason_to_report',
        'admin_quote',
        'status'
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id')->select('id', 'f_name','email','profile_image','role');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id')->select('id', 'f_name','email','profile_image','role');
    }
}
