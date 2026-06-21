<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    protected $fillable = [
        'aff_id',
        'aff_rel_id',
        'comm_amount_5d',
        'comm_percent',
        'status',
    ];    

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'aff_id');
    }

    public function affiliateRelation()
    {
        return $this->belongsTo(AffiliateRelation::class, 'aff_rel_id');
    }
}
