<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    //
    protected $fillable = [
        'from_id',
        'to_id',
        'amount',
        'type'
    ];

     public function fromUser()
     {
         return $this->belongsTo(User::class, 'from_id');
     }
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

}
