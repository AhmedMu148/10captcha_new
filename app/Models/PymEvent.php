<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PymEvent extends Model
{
    /** @use HasFactory<\Database\Factories\PymEventFactory> */
    use HasFactory;

    protected $table = 'pym_events';

    protected $fillable = [
        'uid',
        'payment_id',
        'value',
        'gtm_event',
        'method',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'gtm_event' => 'integer',
        'uid' => 'integer',
        'payment_id' => 'integer',
        'method' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function scopePending($query)
    {
        return $query->where('gtm_event', 0);
    }
}
