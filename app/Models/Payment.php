<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'gateway',
        'payment_provider',
        'payment_reference',
        'payment_hash',
        'txn',
        'amount',
        'currency',
        'description',
        'metadata',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // status constants
    const STATUS_UNCOMPLETED = 0;
    const STATUS_COMPLETED   = 1;
    const STATUS_CANCELED    = 2;

    public function statusLabel(): string
    {
        return match ((int) $this->status) {
            1 => 'completed',
            2 => 'canceled',
            default => 'uncompleted',
        };
    }

    public function statusColor(): string
    {
        return match ((int) $this->status) {
            1 => '#16a34a', // green
            2 => '#dc2626', // red
            default => '#eab308', // yellow
        };
    }

    public function getAmount5dAttribute()
    {
        return $this->amount;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
