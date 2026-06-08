<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table = 'user_details';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'country',
        'mobile',
        'mobile_verify',
        'ref_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
