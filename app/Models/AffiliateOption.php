<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateOption extends Model
{
    protected $table = 'affiliate_option';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'paypal',
        'payoneer',
        'bitcoin',
        'neteller',
        'skrill',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
