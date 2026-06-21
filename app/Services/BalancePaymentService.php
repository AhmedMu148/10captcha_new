<?php

namespace App\Services;

use App\Models\PymEvent;
use App\Models\Payment;
use App\Models\AffiliateRegisterRelation;
use App\Models\AffiliateRelation;
use App\Models\User;
use App\Support\Payments\PaymentProviderResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class BalancePaymentService
{
    public function __construct(private PaymentProviderResolver $paymentProviderResolver) {}

    /**
     * Check if user has sufficient balance for an amount
     */
    public function hasSufficientBalance(User $user, float $amount): bool
    {
        return (float) $user->balance >= $amount;
    }

    /**
     * Deduct balance from user and create Payment record
     *
     * @throws InvalidArgumentException
     */
    public function deductBalance(User $user, float $amount, ?int $orderId = null, string $description = 'Plan purchase via balance'): Payment
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }

        return DB::Payment(function () use ($user, $amount, $orderId, $description) {
            $user = User::lockForUpdate()->find($user->id);

            if (! $user || ! $this->hasSufficientBalance($user, $amount)) {
                throw new InvalidArgumentException('Insufficient balance');
            }

            $user->balance -= $amount;
            $user->save();

            return Payment::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'type' => 'payment',
                'payment_provider' => 'balance',
                'payment_reference' => 'BAL-'.strtoupper(uniqid()),
                'status' => 'completed',
                'amount' => $amount,
                'currency' => 'USD',
                'description' => $description,
                'completed_at' => now(),
            ]);
        });
    }

    /**
     * Add funds to user balance
     *
     * @throws InvalidArgumentException
     */
    public function addBalance(User $user, float $amount, string $description = 'Balance top-up'): Payment
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }
        $uid = $user->id;
        $affiliateRegisterRelation = AffiliateRegisterRelation::where('user_id', $uid);
        if ($affiliateRegisterRelation) {
            $payments = Payment::where('user_id', $uid)->count();
            if ($payments == 1) {
                $aff_id   = $affiliateRegisterRelation['aff_id'];
                $percent    = 10;
                $end_date   = gmdate('ymdHis', strtotime("+3 months"));
                $x = [];
                $x['aff_id']  = $aff_id;
                $x['comm']    = $percent;
                $x['status']  = 'Awaiting';
                $x['end_date']= $end_date;
                AffiliateRelation::updateOrCreate([
                    'user_id' => $uid,
                ], $x);
            }
        }

        return DB::Payment(function () use ($user, $amount, $description) {
            $user = User::lockForUpdate()->find($user->id);
            $user->balance += $amount;
            $user->save();

            return Payment::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'payment_provider' => 'top_up',
                'payment_reference' => 'TOP-'.strtoupper(uniqid()),
                'status' => 'completed',
                'amount' => $amount,
                'currency' => 'USD',
                'description' => $description,
                'completed_at' => now(),
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $gatewayPayment
     * @return array{Payment: Payment|null, user: User|null, credited: bool, reason: string}
     */
    public function completeCentralPaymentTopUp(
        int|string|null $centralPaymentTxId,
        ?string $PaymentHash,
        float $amount,
        string $currency,
        array $gatewayPayment,
        string $gateway,
    ): array {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }

        $identities = $this->paymentIdentities($centralPaymentTxId, $PaymentHash);

        if ($identities === []) {
            return [
                'Payment' => null,
                'user' => null,
                'credited' => false,
                'reason' => 'missing_identity',
            ];
        }

        return DB::Payment(function () use ($identities, $PaymentHash, $amount, $currency, $gatewayPayment, $gateway): array {
            $Payments = $this->centralPaymentTopUpQuery($identities)
                ->lockForUpdate()
                ->get();

            if ($Payments->isEmpty()) {
                return [
                    'Payment' => null,
                    'user' => null,
                    'credited' => false,
                    'reason' => 'not_found',
                ];
            }

            $completedPayment = $this->firstPaymentWithStatus($Payments, ['completed']);

            if ($completedPayment) {
                return [
                    'Payment' => $completedPayment,
                    'user' => null,
                    'credited' => false,
                    'reason' => 'already_completed',
                ];
            }

            $terminalPayment = $this->firstPaymentWithStatus($Payments, ['refunded', 'disputed']);

            if ($terminalPayment) {
                return [
                    'Payment' => $terminalPayment,
                    'user' => null,
                    'credited' => false,
                    'reason' => 'terminal_status',
                ];
            }

            $PaymentRecord = $this->firstPaymentWithStatus($Payments, ['pending', 'failed']);

            if (! $PaymentRecord) {
                return [
                    'Payment' => $Payments->first(),
                    'user' => null,
                    'credited' => false,
                    'reason' => 'not_creditable',
                ];
            }

            $this->assertGatewayAmountAndCurrency($PaymentRecord, $amount, $currency);

            $user = User::query()
                ->whereKey($PaymentRecord->user_id)
                ->lockForUpdate()
                ->first();

            if (! $user) {
                throw new RuntimeException("User {$PaymentRecord->user_id} not found for wallet top-up.");
            }

            $user->balance = round((float) $user->balance + $amount, 2);
            $user->save();

            $PaymentRecord->update([
                'payment_provider' => $this->paymentProviderResolver->fromMetadata(
                    $this->centralPaymentMetadata($PaymentRecord, $gatewayPayment),
                    $gateway
                ),
                'status' => 'completed',
                'payment_hash' => $PaymentHash,
                'metadata' => $this->centralPaymentMetadata($PaymentRecord, $gatewayPayment),
                'completed_at' => now(),
            ]);

            PymEvent::create([
                'uid' => $PaymentRecord->user_id,
                'payment_id' => $PaymentRecord->id,
                'method' => $gateway,
                'value' => $amount,
            ]);

            return [
                'Payment' => $PaymentRecord,
                'user' => $user,
                'credited' => true,
                'reason' => 'credited',
            ];
        }, 5);
    }

    /**
     * @return array{Payment: Payment|null, user: User|null, reversed: bool, reversed_amount: float, reason: string}
     */
    public function refundCentralPaymentTopUp(string $PaymentHash): array
    {
        $identities = $this->paymentIdentities($PaymentHash);

        if ($identities === []) {
            return [
                'Payment' => null,
                'user' => null,
                'reversed' => false,
                'reversed_amount' => 0.0,
                'reason' => 'missing_identity',
            ];
        }

        return DB::Payment(function () use ($identities, $PaymentHash): array {
            $Payments = $this->centralPaymentTopUpQuery($identities)
                ->lockForUpdate()
                ->get();

            if ($Payments->isEmpty()) {
                return [
                    'Payment' => null,
                    'user' => null,
                    'reversed' => false,
                    'reversed_amount' => 0.0,
                    'reason' => 'not_found',
                ];
            }

            $refundedPayment = $this->firstPaymentWithStatus($Payments, ['refunded']);

            if ($refundedPayment) {
                return [
                    'Payment' => $refundedPayment,
                    'user' => null,
                    'reversed' => false,
                    'reversed_amount' => 0.0,
                    'reason' => 'already_refunded',
                ];
            }

            $PaymentRecord = $this->firstPaymentWithStatus($Payments, ['completed']);

            if (! $PaymentRecord) {
                return [
                    'Payment' => $Payments->first(),
                    'user' => null,
                    'reversed' => false,
                    'reversed_amount' => 0.0,
                    'reason' => 'not_completed',
                ];
            }

            $existingRefund = Payment::query()
                ->where('type', 'refund')
                ->where('payment_provider', 'central_payment')
                ->where(function (Builder $query) use ($identities, $PaymentHash): void {
                    $query->where('payment_reference', $PaymentHash.'-refund');

                    foreach ($identities as $identity) {
                        $query->orWhere('payment_hash', $identity);
                    }
                })
                ->lockForUpdate()
                ->first();

            if ($existingRefund) {
                $PaymentRecord->update(['status' => 'refunded']);

                return [
                    'Payment' => $PaymentRecord,
                    'user' => null,
                    'reversed' => false,
                    'reversed_amount' => 0.0,
                    'reason' => 'refund_Payment_exists',
                ];
            }

            $user = User::query()
                ->whereKey($PaymentRecord->user_id)
                ->lockForUpdate()
                ->first();

            if (! $user) {
                throw new RuntimeException("User {$PaymentRecord->user_id} not found for wallet refund.");
            }

            $refundedAmount = (float) $PaymentRecord->amount;
            $reversedAmount = 0.0;

            if ($refundedAmount > 0) {
                $currentBalance = (float) $user->balance;
                $reversedAmount = min($currentBalance, $refundedAmount);

                if ($reversedAmount > 0) {
                    $user->balance = round($currentBalance - $reversedAmount, 2);
                    $user->save();
                }
            }

            $PaymentRecord->update(['status' => 'refunded']);

            if ($reversedAmount > 0) {
                Payment::create([
                    'user_id' => $PaymentRecord->user_id,
                    'order_id' => null,
                    'type' => 'refund',
                    'payment_provider' => 'central_payment',
                    'payment_reference' => $PaymentHash.'-refund',
                    'payment_hash' => $PaymentHash,
                    'status' => 'completed',
                    'amount' => -$reversedAmount,
                    'currency' => $PaymentRecord->currency ?? 'USD',
                    'description' => 'Wallet top-up refund reversal',
                    'metadata' => [
                        'refund_source' => 'central_payment_webhook',
                        'refunded_Payment_id' => $PaymentRecord->id,
                        'requested_refund_amount' => $refundedAmount,
                        'reversed_balance_amount' => $reversedAmount,
                    ],
                    'completed_at' => now(),
                ]);
            }

            return [
                'Payment' => $PaymentRecord,
                'user' => $user,
                'reversed' => $reversedAmount > 0,
                'reversed_amount' => $reversedAmount,
                'reason' => 'refunded',
            ];
        }, 5);
    }

    /**
     * @return array<int, string>
     */
    private function paymentIdentities(mixed ...$values): array
    {
        $identities = [];

        foreach ($values as $value) {
            if (! is_scalar($value)) {
                continue;
            }

            $identity = trim((string) $value);

            if ($identity !== '') {
                $identities[] = $identity;
            }
        }

        return array_values(array_unique($identities));
    }

    /**
     * @param  array<int, string>  $identities
     */
    private function centralPaymentTopUpQuery(array $identities): Builder
    {
        return Payment::query()
            ->whereNull('order_id')
            ->where('type', 'payment')
            ->where(function (Builder $query): void {
                $query->where('payment_provider', 'central_payment')
                    ->orWhere('description', 'Account top-up');
            })
            ->where(function (Builder $query) use ($identities): void {
                foreach ($identities as $identity) {
                    $query->orWhere('payment_reference', $identity)
                        ->orWhere('payment_hash', $identity);
                }
            });
    }

    /**
     * @param  EloquentCollection<int, Payment>  $Payments
     * @param  array<int, string>  $statuses
     */
    private function firstPaymentWithStatus(EloquentCollection $Payments, array $statuses): ?Payment
    {
        return $Payments->first(
            fn (Payment $Payment): bool => in_array($Payment->status, $statuses, true)
        );
    }

    private function assertGatewayAmountAndCurrency(Payment $Payment, float $amount, string $currency): void
    {
        if (abs((float) $Payment->amount - $amount) > 0.00001) {
            throw new InvalidArgumentException("Central Payment amount mismatch for Payment {$Payment->id}.");
        }

        if (strtoupper((string) $Payment->currency) !== strtoupper($currency)) {
            throw new InvalidArgumentException("Central Payment currency mismatch for Payment {$Payment->id}.");
        }
    }

    /**
     * @param  array<string, mixed>  $gatewayPayment
     * @return array<string, mixed>
     */
    private function centralPaymentMetadata(Payment $Payment, array $gatewayPayment): array
    {
        $existingMetadata = $Payment->metadata ?? [];

        return [
            'customer_email' => $gatewayPayment['customer_email'] ?? $existingMetadata['customer_email'] ?? null,
            'payment_method' => $gatewayPayment['payment_method'] ?? $existingMetadata['payment_method'] ?? null,
            'payment_method_label' => $gatewayPayment['payment_method_label'] ?? $existingMetadata['payment_method_label'] ?? null,
            'gateway_reference' => $gatewayPayment['provider_metadata']['gateway_reference'] ?? $gatewayPayment['payment_reference'] ?? $existingMetadata['gateway_reference'] ?? null,
            'paypro_invoice' => $gatewayPayment['paypro_invoice'] ?? $existingMetadata['paypro_invoice'] ?? null,
            'gateway_data' => array_merge(
                $existingMetadata['gateway_data'] ?? [],
                $gatewayPayment
            ),
        ];
    }
}
