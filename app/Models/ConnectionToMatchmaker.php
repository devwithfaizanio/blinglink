<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectionToMatchmaker extends Model
{
    //
    protected $fillable = [
        'payment_history_id',
        'matchmaker_id',
        'user_id'
     ];

        public function paymentHistory()
        {
            return $this->belongsTo(PaymentHistory::class, 'payment_history_id');
        }
        //matchmaker
        public function matchmaker()
        {
            return $this->belongsTo(User::class, 'matchmaker_id');
        }
        //user
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
}
