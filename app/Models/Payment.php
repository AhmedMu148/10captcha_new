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

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
