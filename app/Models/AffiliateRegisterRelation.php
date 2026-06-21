<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateRegisterRelation extends Model
{
    protected $fillable = [
        'aff_id',
        'user_id',
    ];    

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'aff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'user_id', 'user_id');
    }
}
