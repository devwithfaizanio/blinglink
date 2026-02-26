<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectionToMentor extends Model
{
    //
    protected $fillable = [
        'payment_history_id',
        'mentor_id',
        'user_id'
    ];

    public function paymentHistory()
    {
        return $this->belongsTo(PaymentHistory::class, 'payment_history_id');
    }
    //matchmaker
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
    //user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
