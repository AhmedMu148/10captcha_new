<?php

namespace App\Http\Controllers;

use App\Services\CentralPaymentIntegrationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function __construct(private CentralPaymentIntegrationService $payment) {}

    /**
     * Show top-up form
     */
    /**
     * Show top-up form (redirects to dashboard with modal)
     */
    public function showTopUp(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard')->with('open_add_funds_modal', true);
    }

    /**
     * Process top-up request and create hosted payment session
     */
    public function processTopUp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $user = $request->user();

        $amount = (string) number_format((float) $data['amount'], 2, '.', '');
        $currency = strtoupper($data['currency'] ?? 'USD');

        try {
            $paymentData = [
                'amount' => $amount,
                'currency' => $currency,
                'description' => 'Account top-up',
                'customer_email' => $user->email,
                'external_reference' => (string) $user->id,
                'return_url' => route('wallet.success'),
            ];

            $response = $this->payment->createHostedPayment($paymentData);

            // Extract gateway info from the response for display in payment history
            $gatewayInfo = [];
            if (isset($response['gateway'])) {
                $gatewayInfo = [
                    'id' => $response['gateway']['id'] ?? null,
                    'name' => $response['gateway']['name'] ?? null,
                    'display_name' => $response['gateway']['display_name'] ?? null,
                ];
            }

            // Store pending transaction with initial metadata
            // Note: transaction_hash could be numeric ID or hash string from Central Payment
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'payment_provider' => (! empty($gatewayInfo['name']) ? $gatewayInfo['name'] : 'central_payment'),
                'payment_reference' => $response['transaction_hash'] ?? $response['transaction_id'],
                'status' => 'pending',
                'amount' => (float) $amount,
                'currency' => $currency,
                'description' => 'Account top-up',
                'metadata' => [
                    'customer_email' => $user->email,
                    'payment_method' => $gatewayInfo['name'] ?? 'pending',
                    'payment_method_label' => $gatewayInfo['display_name'] ?? 'Payment Gateway (Pending)',
                    'gateway_data' => [
                        'gateway' => $gatewayInfo,
                        'payment_method' => $gatewayInfo['name'] ?? null,
                        'payment_method_label' => $gatewayInfo['display_name'] ?? null,
                        'status' => 'initialized',
                    ],
                ],
            ]);

            
            // Redirect user to hosted checkout URL (hosted_url is validated in service)
            return redirect()->away($response['hosted_url']);
        } catch (\Exception $e) {
            report($e);

            return back()->withErrors(['payment' => 'Unable to create payment session - please try again later.']);
        }
    }

    /**
     * Show success/pending page after redirect from payment provider
     */
    public function topUpSuccess(Request $request): View
    {
        // Pass redirect target based on payment type
        $redirectTo = 'dashboard';
        $isOrderPayment = false;

        // If this is a plan purchase, redirect to orders page after delay
        if ($request->query('source') === 'plan_purchase' || $request->query('subscription_id')) {
            $redirectTo = 'orders.index';
            $isOrderPayment = true;
        }

        // Payment provider may still send webhook later to actually credit the user.
        return view('wallet.success', [
            'redirectTo' => $redirectTo,
            'isOrderPayment' => $isOrderPayment,
        ]);
    }
}
