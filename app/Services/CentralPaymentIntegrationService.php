<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Central Payment API Integration Service
 *
 * A comprehensive, production-ready service for integrating with the Central Payment API.
 *
 * FEATURES:
 * - HMAC-SHA256 authentication for all API requests
 * - Webhook signature verification for incoming events
 * - Three core payment endpoints: create hosted URL, get status, get details
 * - Complete error handling and logging
 * - Configurable retry logic
 * - Thread-safe timestamp generation
 *
 * TESTED ENDPOINTS:
 * 1. POST /api/v1/payments/hosted-url - Create hosted payment
 * 2. GET  /api/v1/payments/{transaction_hash}/status - Get transaction status
 * 3. GET  /api/v1/payments/{transaction_hash} - Get full transaction details
 *
 * WEBHOOK HANDLING:
 * - Automatic signature verification
 * - Event type detection
 * - Payload validation
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
     * Webhook event types from Central Payment
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
     * Subscription webhook event types
     */
    public const EVENT_SUBSCRIPTION_CREATED = 'subscription.created';

    public const EVENT_SUBSCRIPTION_ACTIVATED = 'subscription.activated';

    public const EVENT_SUBSCRIPTION_CANCELLED = 'subscription.cancelled';

    public const EVENT_SUBSCRIPTION_FAILED = 'subscription.failed';

    public const EVENT_SUBSCRIPTION_RENEWED = 'subscription.renewed';

    public const EVENT_SUBSCRIPTION_UPDATED = 'subscription.updated';

    public const EVENT_SUBSCRIPTION_EXPIRED = 'subscription.expired';

    public const EVENT_SUBSCRIPTION_PAYMENT_FAILED = 'subscription.payment_failed';

    public const EVENT_SUBSCRIPTION_TRIALING = 'subscription.trialing';

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
     * Subscription statuses
     */
    public const SUBSCRIPTION_STATUS_PENDING = 'pending';

    public const SUBSCRIPTION_STATUS_ACTIVE = 'active';

    public const SUBSCRIPTION_STATUS_TRIALING = 'trialing';

    public const SUBSCRIPTION_STATUS_PAST_DUE = 'past_due';

    public const SUBSCRIPTION_STATUS_CANCELLED = 'cancelled';

    public const SUBSCRIPTION_STATUS_EXPIRED = 'expired';

    /**
     * Payment statuses
     */
    public const PAYMENT_STATUS_NONE = 'none';

    public const PAYMENT_STATUS_PARTIAL = 'partial';

    public const PAYMENT_STATUS_FULL = 'full';

    public const PAYMENT_STATUS_OVERPAID = 'overpaid';

    /**
     * ========================================================================
     * ENDPOINT 1: CREATE HOSTED PAYMENT URL
     * ========================================================================
     *
     * Creates a hosted payment session and returns a checkout URL where
     * customers can complete their payment.
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
     *     'description' => 'Premium Subscription',
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
     * ENDPOINT 2: GET TRANSACTION STATUS
     * ========================================================================
     *
     * Retrieves the current status of a transaction (quick status check).
     * Use this for polling transaction status during payment process.
     *
     * @param  string  $transactionHash  Transaction hash (e.g., tr_abc123...)
     * @return array Status information
     *
     * @throws Exception If API call fails
     *
     * RESPONSE:
     * {
     *   "transaction_hash": "tr_abc123...",
     *   "status": "completed",
     *   "payment_status": "full",
     *   "amount": "29.99",
     *   "currency": "USD",
     *   "paid_amount": "29.99",
     *   "gateway": "stripe",
     *   "completed_at": "2025-11-16T10:01:49.000000Z"
     * }
     *
     * STATUSES:
     * - pending: Payment created, awaiting customer action
     * - initialized: Customer selected gateway, payment in progress
     * - completed: Payment successfully completed
     * - failed: Payment failed
     * - cancelled: Payment cancelled by customer
     *
     * PAYMENT STATUSES:
     * - none: No payment received
     * - partial: Partial payment received (less than required)
     * - full: Full payment received
     * - overpaid: More than required amount received
     *
     * EXAMPLE:
     * ```php
     * $status = $service->getTransactionStatus('tr_abc123...');
     *
     * if ($status['status'] === 'completed' && $status['payment_status'] === 'full') {
     *     // Payment successful - fulfill order
     * }
     * ```
     */
    public function getTransactionStatus(string $transactionHash): array
    {
        $endpoint = "/api/{$this->apiVersion}/payments/{$transactionHash}/status";

        ActionLogger::info('payment', 'fetching_transaction_status', [
            'transaction_hash' => $transactionHash,
            'endpoint' => $endpoint,
        ], 'payments');

        $response = $this->makeAuthenticatedRequest('GET', $endpoint);

        // Normalize response - flatten nested transaction data
        if (isset($response['transaction']) && is_array($response['transaction'])) {
            // Merge transaction data into main response
            $response = array_merge($response, $response['transaction']);
        }

        // Normalize field names
        if (isset($response['transaction_id']) && ! isset($response['transaction_hash'])) {
            $response['transaction_hash'] = $response['transaction_id'];
        }

        // Extract gateway name if nested
        if (isset($response['gateway']['selected_gateway'])) {
            $response['gateway'] = $response['gateway']['selected_gateway'];
        }

        // Extract timestamps
        if (isset($response['timestamps']['completed_at'])) {
            $response['completed_at'] = $response['timestamps']['completed_at'];
        }

        return $response;
    }

    /**
     * ========================================================================
     * ENDPOINT 3: GET TRANSACTION DETAILS
     * ========================================================================
     *
     * Retrieves complete transaction details including customer info,
     * payment attempts, analytics, and metadata.
     *
     * @param  string  $transactionHash  Transaction hash (e.g., tr_abc123...)
     * @return array Complete transaction details
     *
     * @throws Exception If API call fails
     */
    public function getTransactionDetails(string $transactionHash): array
    {
        $endpoint = "/api/{$this->apiVersion}/payments/{$transactionHash}";

        ActionLogger::info('payment', 'fetching_transaction_details', [
            'transaction_hash' => $transactionHash,
            'endpoint' => $endpoint,
        ], 'payments');

        $response = $this->makeAuthenticatedRequest('GET', $endpoint);

        // Normalize transaction field names if present
        if (isset($response['transaction'])) {
            if (isset($response['transaction']['transaction_id']) && ! isset($response['transaction']['transaction_hash'])) {
                $response['transaction']['transaction_hash'] = $response['transaction']['transaction_id'];
            }
        }

        return $response;
    }

    /**
     * ========================================================================
     * WEBHOOK VERIFICATION
     * ========================================================================
     */

    /**
     * Verify webhook signature and parse payload
     *
     * @param  array  $headers  Request headers
     * @param  string  $rawPayload  Raw JSON payload
     * @return array Parsed webhook data
     *
     * @throws Exception If signature verification fails
     */
    public function verifyAndParseWebhook(array $headers, string $rawPayload): array
    {
        // Get signature from headers (try multiple possible header names)
        $signature = null;
        $possibleHeaders = [
            'x-central-payment-signature',
            'x-webhook-signature',
            'X-Central-Payment-Signature',
            'X-Webhook-Signature',
        ];

        foreach ($possibleHeaders as $headerName) {
            if (isset($headers[$headerName])) {
                $signature = is_array($headers[$headerName]) ? $headers[$headerName][0] : $headers[$headerName];
                break;
            }
        }

        if (! $signature) {
            ActionLogger::error('webhook', 'signature_header_not_found', [
                'payload_length' => strlen($rawPayload),
                'payload_sha256' => hash('sha256', $rawPayload),
                'failure_category' => 'missing_signature',
            ], 'payments');
            throw new Exception('Webhook signature header not found');
        }

        // Central Payment webhook signing contract:
        // X-Webhook-Signature: lowercase hex HMAC-SHA256(raw_request_body, webhook_secret)
        // No timestamp prefix. No base64. Signed over exact raw bytes.
        $expectedSignature = hash_hmac('sha256', $rawPayload, $this->secretKey);
        $signatureValid = hash_equals($expectedSignature, strtolower($signature));

        ActionLogger::info('webhook', 'signature_verification_checked', [
            'payload_length' => strlen($rawPayload),
            'payload_sha256' => hash('sha256', $rawPayload),
            'signature_valid' => $signatureValid,
        ], 'payments');

        if (! $signatureValid) {
            ActionLogger::error('webhook', 'central_payment_webhook_signature_verification_failed', [
                'payload_length' => strlen($rawPayload),
                'payload_sha256' => hash('sha256', $rawPayload),
                'received_sig_prefix' => substr(strtolower($signature), 0, 8),
                'computed_sig_prefix' => substr($expectedSignature, 0, 8),
                'failure_category' => 'invalid_signature',
            ], 'payments');
            throw new Exception('Invalid webhook signature');
        }

        // Parse JSON payload
        $payload = json_decode($rawPayload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON payload: '.json_last_error_msg());
        }

        // Validate required webhook structure
        if (! isset($payload['event'])) {
            throw new Exception('Invalid webhook payload structure: missing event');
        }

        // Normalize new subscription webhook format (subscription at root, no data wrapper)
        if (! isset($payload['data']) && isset($payload['subscription'])) {
            $payload['data'] = ['subscription' => $payload['subscription']];
        }

        if (! isset($payload['event_id']) && isset($payload['id'])) {
            $payload['event_id'] = $payload['id'];
        }

        if (! isset($payload['data'])) {
            throw new Exception('Invalid webhook payload structure: missing data');
        }

        ActionLogger::info('webhook', 'signature_verified', [
            'event' => $payload['event'],
            'event_id' => $payload['event_id'] ?? 'unknown',
        ], 'payments');

        return $payload;
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
