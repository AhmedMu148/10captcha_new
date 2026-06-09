<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\ActionLogger;
use App\Services\CentralPaymentIntegrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentralPaymentWebhookController extends Controller
{
    private CentralPaymentIntegrationService $payment;

    public function __construct()
    {
        $this->payment = new CentralPaymentIntegrationService(
            baseUrl: (string) config('services.central_payment.base_url', ''),
            apiKey: (string) config('services.central_payment.api_key', ''),
            secretKey: (string) config('services.central_payment.webhook_secret', config('services.central_payment.secret_key', '')),
            apiVersion: (string) config('services.central_payment.api_version', 'v1'),
            timeout: (int) config('services.central_payment.timeout', 30),
            verifySSL: (bool) config('services.central_payment.verify_ssl', true)
        );
    }

    public function handle(Request $request): JsonResponse
    {
        $rawPayload = $request->getContent();
        $receiptPayload = json_decode($rawPayload, true);
        $receiptEvent = is_array($receiptPayload) ? ($receiptPayload['event'] ?? null) : null;
        $receiptEventId = is_array($receiptPayload) ? ($receiptPayload['event_id'] ?? $receiptPayload['id'] ?? null) : null;
        $hasSignatureHeader = $request->headers->has('X-Webhook-Signature')
            || $request->headers->has('X-Central-Payment-Signature');

        ActionLogger::info('payment', 'webhook_received', [
            'event' => $receiptEvent,
            'event_id' => $receiptEventId,
            'payload_length' => strlen($rawPayload),
            'payload_sha256' => hash('sha256', $rawPayload),
            'has_signature_header' => $hasSignatureHeader,
            'has_timestamp_header' => $request->headers->has('X-Webhook-Timestamp'),
        ], 'payments');

        try {
            $webhookData = $this->payment->verifyAndParseWebhook(
                $request->headers->all(),
                $rawPayload
            );

            $event = $webhookData['event'] ?? null;
            $transaction = $webhookData['data']['transaction'] ?? [];

            ActionLogger::info('payment', 'processing_central_payment_webhook', [
                'event' => $event,
                'transaction_id' => $transaction['id'] ?? null,
                'transaction_hash' => $transaction['transaction_hash'] ?? null,
            ], 'payments');

            switch ($event) {
                case CentralPaymentIntegrationService::EVENT_TRANSACTION_COMPLETED:
                    $this->handleTransactionCompleted($transaction);
                    break;

                case CentralPaymentIntegrationService::EVENT_TRANSACTION_FAILED:
                case CentralPaymentIntegrationService::EVENT_TRANSACTION_CANCELLED:
                case CentralPaymentIntegrationService::EVENT_TRANSACTION_EXPIRED:
                case CentralPaymentIntegrationService::EVENT_TRANSACTION_REFUNDED:
                case CentralPaymentIntegrationService::EVENT_TRANSACTION_REVERSED:
                    $this->handleBalanceReversal($transaction, $event);
                    break;

                default:
                    ActionLogger::info('payment', 'ignored_central_payment_event', [
                        'event' => $event,
                    ], 'payments');
                    break;
            }

            return response()->json(['success' => true], 200);
        } catch (\Throwable $e) {
            ActionLogger::error('payment', 'central_payment_webhook_error', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ], 'payments');

            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    private function handleTransactionCompleted(array $transaction): void
    {
        $centralPaymentTxId = $transaction['id'] ?? null;
        $transactionHash = $transaction['transaction_hash'] ?? null;
        $amount = (float) ($transaction['amount'] ?? 0);
        $currency = strtoupper((string) ($transaction['currency'] ?? 'USD'));
        $gateway = $this->normalizeGatewayName($transaction['gateway'] ?? null);

        if ($amount <= 0 || (! $centralPaymentTxId && ! $transactionHash)) {
            ActionLogger::warning('payment', 'transaction_completed_skipped', [
                'amount' => $amount,
                'transaction' => $transaction,
            ], 'payments');

            return;
        }

        if ($this->isPlanPurchase($transaction)) {
            ActionLogger::info('payment', 'transaction_completed_skipped_plan_purchase', [
                'transaction' => $transaction,
            ], 'payments');

            return;
        }

        $payment = $this->findPendingTopUpPayment($centralPaymentTxId, $transactionHash);
        if (! $payment) {
            ActionLogger::warning('payment', 'balance_topup_no_pending_payment', [
                'central_payment_tx_id' => $centralPaymentTxId,
                'transaction_hash' => $transactionHash,
            ], 'payments');

            return;
        }

        $user = $payment->user;
        if (! $user) {
            ActionLogger::warning('payment', 'balance_topup_no_user', [
                'payment_id' => $payment->id,
            ], 'payments');

            return;
        }

        $creditAmount = $this->toInternalBalance($amount);

        DB::transaction(function () use ($payment, $user, $amount, $currency, $gateway, $transactionHash, $centralPaymentTxId, $transaction, $creditAmount) {
            $payment->status = Payment::STATUS_COMPLETED;
            $payment->currency = $currency;
            $payment->gateway = $gateway;
            $payment->payment_hash = $transactionHash ?? $centralPaymentTxId;
            $payment->metadata = array_merge($payment->metadata ?? [], $transaction);
            $payment->amount = $creditAmount;
            $payment->save();

            $user->balance_5d = (int) max(0, $user->balance_5d + $creditAmount);
            $user->save();
        });

        ActionLogger::info('payment', 'balance_topup_completed', [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => $currency,
            'balance_5d' => $user->balance_5d,
        ], 'payments');
    }

    private function handleBalanceReversal(array $transaction, string $eventType): void
    {
        $centralPaymentTxId = $transaction['id'] ?? null;
        $transactionHash = $transaction['transaction_hash'] ?? null;

        if (! $centralPaymentTxId && ! $transactionHash) {
            return;
        }

        $payment = $this->findCompletedTopUpPayment($centralPaymentTxId, $transactionHash);
        if (! $payment) {
            return;
        }

        $user = $payment->user;
        if (! $user) {
            return;
        }

        $reversalAmount = min($user->balance_5d, (int) ($payment->amount ?? 0));
        if ($reversalAmount <= 0) {
            return;
        }

        DB::transaction(function () use ($payment, $user, $reversalAmount, $eventType) {
            $user->balance_5d = (int) max(0, $user->balance_5d - $reversalAmount);
            $user->save();

            $payment->status = Payment::STATUS_CANCELED;
            $payment->metadata = array_merge($payment->metadata ?? [], [
                'central_payment_lifecycle' => [
                    'event' => $eventType,
                    'reversed_amount' => $reversalAmount,
                ],
            ]);
            $payment->save();
        });

        ActionLogger::info('payment', 'balance_topup_reversed', [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'event' => $eventType,
            'reversed_amount' => $reversalAmount,
        ], 'payments');
    }

    private function findPendingTopUpPayment(?string $centralPaymentTxId, ?string $transactionHash): ?Payment
    {
        return Payment::where('payment_provider', 'central_payment')
            ->where('status', Payment::STATUS_UNCOMPLETED)
            ->where(function ($query) use ($centralPaymentTxId, $transactionHash) {
                if ($centralPaymentTxId) {
                    $query->where('payment_reference', $centralPaymentTxId);
                }
                if ($transactionHash) {
                    $query->orWhere('payment_reference', $transactionHash);
                }
            })
            ->first();
    }

    private function findCompletedTopUpPayment(?string $centralPaymentTxId, ?string $transactionHash): ?Payment
    {
        return Payment::where('payment_provider', 'central_payment')
            ->where('status', Payment::STATUS_COMPLETED)
            ->where(function ($query) use ($centralPaymentTxId, $transactionHash) {
                if ($centralPaymentTxId) {
                    $query->where('payment_reference', $centralPaymentTxId);
                }
                if ($transactionHash) {
                    $query->orWhere('payment_reference', $transactionHash);
                }
            })
            ->first();
    }

    private function normalizeGatewayName(mixed $gateway): string
    {
        if (is_array($gateway)) {
            return (string) ($gateway['name'] ?? $gateway['display_name'] ?? $gateway['id'] ?? 'central_payment');
        }

        return (string) ($gateway ?? 'central_payment');
    }

    private function toInternalBalance(float $amount): int
    {
        return (int) round($amount * 100000);
    }

    private function isPlanPurchase(array $transaction): bool
    {
        $metadata = is_array($transaction['metadata'] ?? null) ? $transaction['metadata'] : [];
        return isset($metadata['plan_id']) || isset($metadata['_plan_id']) || isset($metadata['order_id']);
    }
}
