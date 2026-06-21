<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateBalance extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'aff_id',
        'user_id',
        'balance_5d',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'aff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
