<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateWithdraw extends Model
{

    protected $fillable = [
        'user_id',
        'txn_id',
        'amount_5d',
        'method',
        'payment_email',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
