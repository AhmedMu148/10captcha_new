<?php

namespace App\Livewire;

use App\Services\CentralPaymentIntegrationService;
use App\Support\GtmHelper;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class AddFundModal extends Component
{
    public $amount = 0;

    public string $currency = 'USD';

    public bool $isProcessing = false;

    public string $errorMessage = '';

    public float $requiredAmount = 0;

    public string $reason = '';

    /**
     * @var array<int>
     */
    public array $presets = [15, 90, 300, 1500];

    /**
     * Handle amount updates and sanitize input
     */
    public function updatedAmount($value): void
    {
        // Clear previous error when user starts typing
        $this->errorMessage = '';

        // Handle null or empty values
        if ($value === null || $value === '') {
            $this->amount = 0;

            return;
        }

        // Remove any non-numeric characters except decimal point and minus sign
        $sanitized = preg_replace('/[^0-9.-]/', '', (string) $value);

        // Handle multiple decimal points (keep only the first one)
        $parts = explode('.', $sanitized);
        if (count($parts) > 2) {
            $sanitized = $parts[0].'.'.implode('', array_slice($parts, 1));
        }

        // Handle multiple minus signs (keep only if at the start)
        $sanitized = preg_replace('/(?!^)-/', '', $sanitized);

        // Convert to float
        $numericValue = $sanitized !== '' && $sanitized !== '-' ? (float) $sanitized : 0;

        // Ensure it's a valid number (not NaN or Infinite)
        if (! is_numeric($numericValue) || ! is_finite($numericValue)) {
            $this->amount = 0;
            $this->errorMessage = 'Please enter a valid amount.';

            return;
        }

        // Don't allow negative values
        if ($numericValue < 0) {
            $this->amount = 0;
            $this->errorMessage = 'Amount cannot be negative.';

            return;
        }

        // Round to 2 decimal places to prevent precision issues
        $this->amount = round($numericValue, 2);
    }

    /**
     * Open the add fund modal with optional pre-filled amount
     */
    #[On('open-add-fund-modal')]
    public function openModal(float $amount = 0, string $reason = ''): void
    {
        $this->reset(['amount', 'requiredAmount', 'reason', 'errorMessage']);
        $this->amount = $amount > 0 ? $amount : 0;
        $this->requiredAmount = $amount;
        $this->reason = $reason;
        $this->errorMessage = '';
        $this->dispatch('open-modal', 'add-fund-modal');
    }

    /**
     * Set amount from preset buttons
     */
    public function setPreset(float $preset): void
    {
        $this->amount = $preset;
    }

    /**
     * Process the top-up request
     */
    public function processTopUp(): void
    {
        $this->errorMessage = '';

        // Validate that amount is a valid number
        if (! is_numeric($this->amount) || ! is_finite($this->amount)) {
            $this->errorMessage = 'Please enter a valid amount.';

            return;
        }

        // Minimum amount validation
        if ($this->amount < 1) {
            $this->errorMessage = 'Please enter an amount of at least $1.00';

            return;
        }

        $user = Auth::user();
        if (! $user) {
            $this->errorMessage = 'Please log in to continue.';

            return;
        }

        $gtmData = GtmHelper::gtmData('checkout', [
            'value' => $this->amount,
            'method' => 'central_payment',
        ]);

        if (is_array($gtmData)) {
            $json = json_encode($gtmData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->js("window.dataLayer = window.dataLayer || []; window.dataLayer.push($json);");
        }

        $this->isProcessing = true;

        try {
            $payment = app(CentralPaymentIntegrationService::class);

            $amountFormatted = number_format($this->amount, 2, '.', '');

            // Get customer name with fallback to email prefix if name is empty
            $customerName = $user->name;
            if (empty($customerName)) {
                $customerName = explode('@', $user->email)[0];
            }

            $paymentData = [
                'amount' => $amountFormatted,
                'currency' => $this->currency,
                'description' => 'Account top-up',
                'customer_email' => $user->email,
                'customer_name' => $customerName,
                'external_reference' => (string) $user->id,
                'return_url' => route('wallet.success'),
            ];

            $response = $payment->createHostedPayment($paymentData);

            // Extract gateway info from the response for display in payment history
            $gatewayInfo = [];
            if (isset($response['gateway'])) {
                $gatewayInfo = [
                    'id' => $response['gateway']['id'] ?? null,
                    'name' => $response['gateway']['name'] ?? null,
                    'display_name' => $response['gateway']['display_name'] ?? null,
                ];
            }

            // Get transaction ID from response (try multiple field names)
            $transactionId = $response['transaction_hash'] ?? $response['transaction_id'] ?? $response['id'] ?? null;
            if (!$transactionId) {
                throw new \Exception('API did not return a transaction ID');
            }

            // Store pending Payment with initial metadata
            Payment::create([
                'user_id' => $user->id,
                'gateway' => $gatewayInfo['name'] ?? 'central_payment',
                'payment_provider' => 'central_payment',
                'payment_reference' => $transactionId,
                'status' => 0,  // 0 = uncompleted/pending
                'amount' => (float) $amountFormatted,  // stored x100000
                'currency' => $this->currency,
                'description' => 'Account top-up',
                'metadata' => [
                    'customer_email' => $user->email,
                    'payment_method' => $gatewayInfo['name'] ?? 'pending',
                    'payment_method_label' => $gatewayInfo['display_name'] ?? 'Payment Method Pending',
                    'gateway_data' => [
                        'gateway' => $gatewayInfo,
                        'payment_method' => $gatewayInfo['name'] ?? null,
                        'payment_method_label' => $gatewayInfo['display_name'] ?? null,
                        'status' => 'initialized',
                    ],
                ],
            ]);

            // Close modal and redirect
            $this->reset(['amount', 'requiredAmount', 'reason', 'errorMessage']);
            $this->dispatch('close-modal', 'add-fund-modal');
            
            // Get hosted URL from response (try multiple field names)
            $hostedUrl = $response['hosted_url'] ?? $response['checkout_url'] ?? $response['url'] ?? null;
            if (!$hostedUrl) {
                throw new \Exception('API did not return a hosted payment URL');
            }
            
            $this->dispatch('redirect-to-payment', url: $hostedUrl);
        } catch (\Exception $e) {
            report($e);
            $this->errorMessage = 'Unable to create payment session. Please try again.';
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Close the modal
     */
    public function closeModal(): void
    {
        $this->dispatch('close-modal', 'add-fund-modal');
        $this->reset(['amount', 'requiredAmount', 'reason', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.add-fund-modal');
    }
}
