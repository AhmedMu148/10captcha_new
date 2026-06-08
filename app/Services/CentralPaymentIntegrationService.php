<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Central Payment API Integration Service
 *
 * A focused, production-ready service for integrating with the Central Payment API.
 *
 * FEATURES:
 * - HMAC-SHA256 authentication for all API requests
 * - One core payment endpoint: create hosted payment URL for add funds
 * - Complete error handling and logging
 *
 * TESTED ENDPOINTS:
 * 1. POST /api/v1/payments/hosted-url - Create hosted payment (Add Funds)
 *
 * @version 1.0.0
 *
 * @author Central Payment Integration Team
 *
 * @tested 2025-11-16
 */
class CentralPaymentIntegrationService
{
    /**
     * Central Payment API Configuration
     */
    private string $baseUrl;

    private string $apiVersion;

    private string $apiKey;

    private string $secretKey;

    private int $timeout;

    private bool $verifySSL;

    /**
     * Transaction event types from Central Payment
     */
    public const EVENT_TRANSACTION_CREATED = 'transaction.created';

    public const EVENT_TRANSACTION_UPDATED = 'transaction.updated';

    public const EVENT_TRANSACTION_COMPLETED = 'transaction.completed';

    public const EVENT_TRANSACTION_FAILED = 'transaction.failed';

    public const EVENT_TRANSACTION_CANCELLED = 'transaction.cancelled';

    public const EVENT_TRANSACTION_EXPIRED = 'transaction.expired';

    public const EVENT_TRANSACTION_REFUNDED = 'transaction.refunded';

    public const EVENT_TRANSACTION_REVERSED = 'transaction.reversed';

    public const EVENT_TRANSACTION_PARTIAL_PAYMENT = 'transaction.partial_payment';

    public const EVENT_TRANSACTION_DISPUTED = 'transaction.disputed';

    /**
     * Transaction statuses
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_INITIALIZED = 'initialized';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REFUNDED = 'refunded';

    /**
     * Payment statuses
     */
    public const PAYMENT_STATUS_NONE = 'none';

    public const PAYMENT_STATUS_PARTIAL = 'partial';

    public const PAYMENT_STATUS_FULL = 'full';

    public const PAYMENT_STATUS_OVERPAID = 'overpaid';

    /**
     * Initialize the integration service
     *
     * @param  string  $baseUrl  Base URL of Central Payment API (e.g., https://billing.flare99.com)
     * @param  string  $apiKey  Your application's API key
     * @param  string  $secretKey  Your application's secret key (for HMAC signing)
     * @param  string  $apiVersion  API version (default: v1)
     * @param  int  $timeout  Request timeout in seconds (default: 30)
     * @param  bool  $verifySSL  Verify SSL certificates (default: true, set false for local testing)
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        string $secretKey,
        string $apiVersion = 'v1',
        int $timeout = 30,
        bool $verifySSL = true
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->apiVersion = $apiVersion;
        $this->timeout = $timeout;
        $this->verifySSL = $verifySSL;
    }

    /**
     * ========================================================================
     * CREATE HOSTED PAYMENT (ADD FUNDS)
     * ========================================================================
     *
     * Creates a hosted payment session and returns a checkout URL where
     * customers can complete their payment to add funds.
     *
     * @param  array  $paymentData  Payment details
     * @return array Response with transaction_hash and hosted_url
     *
     * @throws Exception If API call fails
     *
     * REQUIRED FIELDS:
     * - amount (string|float): Payment amount (e.g., "29.99")
     * - currency (string): ISO currency code (e.g., "USD")
     * - description (string): Payment description
     * - customer_email (string): Customer email address
     *
     * OPTIONAL FIELDS:
     * - external_reference (string): Your order/reference ID
     * - customer_name (string): Customer full name
     * - customer_phone (string): Customer phone number
     * - return_url (string): URL to redirect after payment
     * - metadata (array): Custom data to store with transaction
     *
     * RESPONSE:
     * {
     *   "success": true,
     *   "transaction_hash": "tr_abc123...",
     *   "hosted_url": "https://billing.flare99.com/hosted-payment/tr_abc123...",
     *   "expires_at": "2025-11-17T10:00:00.000000Z"
     * }
     *
     * EXAMPLE:
     * ```php
     * $payment = $service->createHostedPayment([
     *     'amount' => '29.99',
     *     'currency' => 'USD',
     *     'description' => 'Add Funds',
     *     'customer_email' => 'customer@example.com',
     *     'customer_name' => 'John Doe',
     *     'external_reference' => 'ORDER-12345',
     *     'return_url' => 'https://yoursite.com/payment/success'
     * ]);
     *
     * // Redirect customer to: $payment['hosted_url']
     * ```
     */
    public function createHostedPayment(array $paymentData): array
    {
        $endpoint = "/api/{$this->apiVersion}/payments/hosted-url";

        // Validate required fields
        $requiredFields = ['amount', 'currency', 'description', 'customer_email'];
        foreach ($requiredFields as $field) {
            if (! isset($paymentData[$field]) || empty($paymentData[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }

        // Prepare payload
        $payload = [
            'amount' => (string) $paymentData['amount'],
            'currency' => strtoupper($paymentData['currency']),
            'description' => $paymentData['description'],
            'customer_email' => $paymentData['customer_email'],
        ];

        // Add optional fields - ensure values are not empty strings
        if (! empty($paymentData['external_reference'])) {
            $payload['external_reference'] = $paymentData['external_reference'];
        }
        if (! empty($paymentData['customer_name'])) {
            $payload['customer_name'] = $paymentData['customer_name'];
        }
        if (! empty($paymentData['customer_phone'])) {
            $payload['customer_phone'] = $paymentData['customer_phone'];
        }
        if (isset($paymentData['return_url'])) {
            $payload['return_url'] = $paymentData['return_url'];
        }
        if (isset($paymentData['metadata'])) {
            $payload['metadata'] = $paymentData['metadata'];
        }

        ActionLogger::info('payment', 'creating_hosted_payment', [
            'endpoint' => $endpoint,
            'amount' => $payload['amount'],
            'currency' => $payload['currency'],
            'customer_email' => $payload['customer_email'],
        ], 'payments');

        $response = $this->makeAuthenticatedRequest('POST', $endpoint, $payload);

        // Normalize the response to match expected format
        // API returns: transaction_id, checkout_url
        // We want: transaction_hash, hosted_url
        if (isset($response['transaction_id'])) {
            $response['transaction_hash'] = $response['transaction_id'];
        }
        if (isset($response['checkout_url'])) {
            $response['hosted_url'] = $response['checkout_url'];
        }

        if (! isset($response['transaction_hash']) || ! isset($response['hosted_url'])) {
            ActionLogger::error('payment', 'invalid_response_from_payment_api', [
                'response' => $response,
            ], 'payments');
            throw new Exception('Invalid response from payment API: missing transaction fields. Response: '.json_encode($response));
        }

        return $response;
    }

    /**
     * ========================================================================
     * PRIVATE METHODS - HTTP REQUEST HANDLING
     * ========================================================================
     */

    /**
     * Make authenticated API request with HMAC-SHA256 signature
     *
     * @param  string  $method  HTTP method (GET, POST, PUT, DELETE)
     * @param  string  $endpoint  API endpoint path
     * @param  array  $data  Request payload (for POST/PUT)
     * @return array Response data
     *
     * @throws Exception If request fails
     */
    private function makeAuthenticatedRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl.$endpoint;
        $timestamp = time();

        // Prepare request body
        $body = '';
        if (! empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        }

        // Generate HMAC signature
        // Format: base64(hmac-sha256("{timestamp}.{body}", secretKey))
        $signature = base64_encode(hash_hmac('sha256', "{$timestamp}.{$body}", $this->secretKey, true));

        // Prepare headers
        $headers = [
            'X-Api-Key' => $this->apiKey,
            'X-Signature' => $signature,
            'X-Timestamp' => (string) $timestamp,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'CentralPaymentIntegration/1.0',
        ];

        ActionLogger::info('integration', 'making_authenticated_api_request', [
            'method' => $method,
            'url' => $url,
            'timestamp' => $timestamp,
            'has_body' => ! empty($body),
        ], 'payments');

        try {
            // Make HTTP request
            $httpClient = Http::withHeaders($headers)
                ->timeout($this->timeout);

            // Disable SSL verification if configured (for local testing only)
            if (! $this->verifySSL) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }

            $response = match ($method) {
                'GET' => $httpClient->get($url),
                'POST' => $httpClient->send('POST', $url, ['body' => $body]),
                'PUT' => $httpClient->send('PUT', $url, ['body' => $body]),
                'DELETE' => $httpClient->delete($url),
                default => throw new Exception("Unsupported HTTP method: {$method}")
            };

            // Check response status
            if (! $response->successful()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['message'] ?? $errorBody['error'] ?? 'Unknown error';

                ActionLogger::error('integration', 'api_request_failed', [
                    'method' => $method,
                    'url' => $url,
                    'status' => $response->status(),
                    'error' => $errorMessage,
                    'response' => $errorBody,
                ], 'payments');

                throw new Exception("API request failed ({$response->status()}): {$errorMessage}");
            }

            $responseData = $response->json();

            ActionLogger::info('integration', 'api_request_successful', [
                'method' => $method,
                'url' => $url,
                'status' => $response->status(),
            ], 'payments');

            return $responseData;

        } catch (Exception $e) {
            ActionLogger::error('integration', 'api_request_exception', [
                'method' => $method,
                'url' => $url,
                'error' => $e->getMessage(),
            ], 'payments');
            throw $e;
        }
    }

    /**
     * ========================================================================
     * UTILITY METHODS
     * ========================================================================
     */

    /**
     * Check if transaction is completed and fully paid
     *
     * @param  array  $transaction  Transaction data
     * @return bool True if payment is complete and full
     */
    public function isPaymentComplete(array $transaction): bool
    {
        return isset($transaction['status'])
            && $transaction['status'] === self::STATUS_COMPLETED
            && isset($transaction['payment_status'])
            && $transaction['payment_status'] === self::PAYMENT_STATUS_FULL;
    }

    /**
     * Check if transaction is pending customer action
     *
     * @param  array  $transaction  Transaction data
     * @return bool True if awaiting customer payment
     */
    public function isPending(array $transaction): bool
    {
        return isset($transaction['status'])
            && in_array($transaction['status'], [self::STATUS_PENDING, self::STATUS_INITIALIZED, self::STATUS_PROCESSING]);
    }

    /**
     * Check if transaction has failed
     *
     * @param  array  $transaction  Transaction data
     * @return bool True if payment failed
     */
    public function hasFailed(array $transaction): bool
    {
        return isset($transaction['status'])
            && in_array($transaction['status'], [self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    /**
     * Get configuration summary (for debugging)
     *
     * @return array Configuration details (without sensitive data)
     */
    public function getConfig(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'api_version' => $this->apiVersion,
            'api_key' => substr($this->apiKey, 0, 8).'...',
            'timeout' => $this->timeout,
            'verify_ssl' => $this->verifySSL,
        ];
    }
}
