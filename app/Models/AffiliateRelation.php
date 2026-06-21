<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateRelation extends Model
{
    protected $fillable = [
        'aff_id',
        'user_id',
        'comm',
        'status',
        'end_date',
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
