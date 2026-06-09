<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookEvent extends Model
{
    public const STATUS_RECEIVED = 'received';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_DUPLICATE = 'duplicate';

    public const PROVIDER_PAYPAL = 'paypal';

    public const PROVIDER_PAYPRO = 'paypro';

    public const PROVIDER_CENTRAL = 'central_payment';

    protected $fillable = [
        'provider',
        'event_type',
        'event_id',
        'provider_event_key',
        'event_timestamp',
        'canonical_subscription_hash',
        'subscr_id',
        'user_id',
        'order_id',
        'payload',
        'status',
        'error_message',
        'processed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'event_timestamp' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function markAsProcessed(?int $orderId = null): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSED,
            'order_id' => $orderId,
            'processed_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
            'processed_at' => now(),
        ]);
    }

    public function markAsDuplicate(): void
    {
        $this->update([
            'status' => self::STATUS_DUPLICATE,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function duplicateLogContext(): array
    {
        return [
            'original_event_type' => $this->event_type,
            'original_status' => $this->status,
            'original_processed_at' => $this->processed_at?->toISOString(),
            'original_error_recorded' => filled($this->error_message),
        ];
    }
}
