<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id',
        'f_name',
        'l_name',
        'software_name',
        'software_link',
        'message',
        'hash',
        'promo_link',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
