<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ActionLogger
{
    private const RAW_CONTEXT_KEYS = [
        'api_key',
        'authorization',
        'available_headers',
        'data',
        'expected_signature',
        'gateway_data',
        'headers',
        'payload',
        'payload_base64',
        'payload_first_100',
        'payload_last_100',
        'provider_metadata',
        'raw_payload',
        'received_signature',
        'secret',
        'secret_key',
        'signature',
        'token',
        'webhook_secret',
    ];

    private const PAYMENT_REFERENCE_CONTEXT_KEYS = [
        'central_payment_subscription_id',
        'external_id',
        'external_subscription_hash',
        'gateway_reference',
        'original_payment_hash',
        'original_payment_reference',
        'original_transaction_hash',
        'payment_hash',
        'payment_reference',
        'provider_transaction_reference',
        'subscription_hash',
        'subscription_reference',
        'transaction_hash',
        'txn_id',
    ];

    protected static function requestId(): string
    {
        if (function_exists('request') && request()) {
            $rid = request()->header('X-Request-Id') ?? request()->header('X-Correlation-Id');
            if ($rid) {
                return (string) $rid;
            }
        }

        return Str::uuid()->toString();
    }

    protected static function baseContext(string $component, array $context = []): array
    {
        $base = [
            'component' => $component,
            'request_id' => self::requestId(),
        ];

        return array_merge($base, self::sanitizeContext($context));
    }

    public static function debug(string $component, string $message, array $context = [], ?string $channel = null): void
    {
        $channel = $channel ?? ($context['channel'] ?? 'auth');
        $prefix = '🔍 ';
        try {
            Log::channel($channel)->debug($prefix.$message, self::baseContext($component, $context));
        } catch (\Throwable $e) {
            Log::channel('daily')->error('ActionLogger write failed: '.$e->getMessage(), self::baseContext($component, array_merge($context, ['original_message' => $message])));
        }
    }

    public static function info(string $component, string $message, array $context = [], ?string $channel = null): void
    {
        $channel = $channel ?? ($context['channel'] ?? 'auth');
        $prefix = 'ℹ️ ';
        try {
            Log::channel($channel)->info($prefix.$message, self::baseContext($component, $context));
        } catch (\Throwable $e) {
            Log::channel('daily')->error('ActionLogger write failed: '.$e->getMessage(), self::baseContext($component, array_merge($context, ['original_message' => $message])));
        }
    }

    public static function success(string $component, string $message, array $context = [], ?string $channel = null): void
    {
        $ctx = array_merge(['status' => 'success'], $context);
        $channel = $channel ?? ($context['channel'] ?? 'auth');
        $prefix = '✅ ';
        try {
            Log::channel($channel)->info($prefix.$message, self::baseContext($component, $ctx));
        } catch (\Throwable $e) {
            Log::channel('daily')->error('ActionLogger write failed: '.$e->getMessage(), self::baseContext($component, array_merge($ctx, ['original_message' => $message])));
        }
    }

    public static function warning(string $component, string $message, array $context = [], ?string $channel = null): void
    {
        $ctx = array_merge(['status' => 'warning'], $context);
        $channel = $channel ?? ($context['channel'] ?? 'auth');
        $prefix = '⚠️ ';
        try {
            Log::channel($channel)->warning($prefix.$message, self::baseContext($component, $ctx));
        } catch (\Throwable $e) {
            Log::channel('daily')->error('ActionLogger write failed: '.$e->getMessage(), self::baseContext($component, array_merge($ctx, ['original_message' => $message])));
        }
    }

    public static function error(string $component, string $message, array $context = [], ?string $channel = null): void
    {
        $ctx = array_merge(['status' => 'error'], $context);
        $channel = $channel ?? ($context['channel'] ?? 'auth');
        $prefix = '❌ ';
        try {
            Log::channel($channel)->error($prefix.$message, self::baseContext($component, $ctx));
        } catch (\Throwable $e) {
            Log::channel('daily')->error('ActionLogger write failed: '.$e->getMessage(), self::baseContext($component, array_merge($ctx, ['original_message' => $message])));
        }
    }

    /**
     * @param  array<string|int, mixed>  $context
     * @return array<string|int, mixed>
     */
    private static function sanitizeContext(array $context): array
    {
        $sanitized = [];

        foreach ($context as $key => $value) {
            $normalizedKey = strtolower((string) $key);

            if (in_array($normalizedKey, self::RAW_CONTEXT_KEYS, true)) {
                $sanitized[$key] = '[redacted]';

                continue;
            }

            if (in_array($normalizedKey, self::PAYMENT_REFERENCE_CONTEXT_KEYS, true)) {
                $sanitized[$key] = self::fingerprintReference($value);

                continue;
            }

            $sanitized[$key] = is_array($value) ? self::sanitizeContext($value) : $value;
        }

        return $sanitized;
    }

    /**
     * @return array{sha256: string, suffix: string}|mixed
     */
    private static function fingerprintReference(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (! is_scalar($value)) {
            return '[redacted]';
        }

        $reference = (string) $value;

        return [
            'sha256' => hash('sha256', $reference),
            'suffix' => substr($reference, -8),
        ];
    }
}
