<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\AffiliateBalance;
use App\Models\AffiliateCommission;
use App\Models\AffiliateOption;
use App\Models\AffiliateWithdraw;

class AffiliateController extends Controller
{
    public function registerRelation()
    {
        $affiliate = Affiliate::where('user_id', auth()->user()->id)->first();
        if ($affiliate->status != 'Approve') {
            return back()->with('error', 'You are not authorized to access this page.');
        }
        return view('affiliate.partnership-register-relation', compact('affiliate'));
    }

    public function withdraws()
    {
        $affiliate = auth()->user()->affiliate;
        $methods = [
            'PayPal',
            'Payoneer',
            'Bitcoin',
            'Neteller',
            'Skrill',
            'Account Balance'
        ];
        return view('affiliate.partnership-withdraw', compact('affiliate', 'methods'));
    }

    public function withdrawStore(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
        ]);

        $uid = auth()->user();
        $affiliateBalance = AffiliateBalance::where('user_id', $uid->id)->first();

        // Check if the affiliate has enough balance
        if (!$affiliateBalance || $affiliateBalance->balance_5d < ($request->amount * 100000)) {
            return back()->with('error', 'Insufficient balance for this withdrawal.');
        }

        // Get the payment email based on selected method
        $paymentEmail = null;
        if ($request->method !== 'Account Balance') {
            $options = AffiliateOption::where('user_id', $uid->id)->first();
            $methodKey = strtolower($request->method); // 'paypal', 'payoneer', 'bitcoin', 'neteller', 'skrill'
            if (!$options || empty($options->$methodKey)) {
                return back()->with('error', 'Please configure your ' . $request->method . ' payout email in the Options page before withdrawing.');
            }
            $paymentEmail = $options->$methodKey;
        } else {
            $paymentEmail = $uid->email;
        }

        // Generate a unique transaction ID
        $txnId = 'WD-' . strtoupper(bin2hex(random_bytes(5)));

        // Create a new withdrawal request
        AffiliateWithdraw::create([
            'user_id' => $uid->id,
            'txn_id' => $txnId,
            'amount_5d' => $request->amount * 100000,
            'method' => $request->method,
            'payment_email' => $paymentEmail,
            'status' => 'Awaiting',
        ]);

        // Deduct from affiliate balance
        $affiliateBalance->decrement('balance_5d', $request->amount * 100000);

        return back()->with('success', 'Your withdrawal request has been submitted successfully.');
    }

    public function partnership()
    {
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            $affiliate = null;
            return view('affiliate.partnership', compact('affiliate'));
        }

        if ($affiliate->status == 'Awaiting') {
            return view('affiliate.partnership', compact('affiliate'));
        }

        if ($affiliate->status == 'Approve') {
            return view('affiliate.partnership-commission', compact('affiliate'));
        }

        if ($affiliate->status == 'Unapprove') {
            return view('affiliate.partnership', compact('affiliate'));
        }
    }

    public function partnershipStore(Request $request)
    {
        $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'software_name' => 'nullable|string|max:255',
            'software_link' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if ($affiliate) {
            return back()->with('error', 'You have already submitted an application.');
        }

        Affiliate::create([
            'user_id' => $user->id,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'software_name' => $request->software_name ?? '',
            'software_link' => $request->software_link ?? '',
            'message' => $request->message,
            'status' => 'Awaiting',
        ]);

        return back()->with('success', 'Your partnership application has been submitted successfully.');
    }

    public function partnershipOption()
    {
        $user = auth()->user();
        $affiliateOption = AffiliateOption::with('user')
            ->where('user_id', $user->id)
            ->first();
        return view('affiliate.partnership-option', compact('affiliateOption'));
    }

    public function optionStore(Request $request)
    {
        $request->validate([
            'paypal' => 'nullable|email',
            'payoneer' => 'nullable|email',
            'bitcoin' => 'nullable|email',
            'neteller' => 'nullable|email',
            'skrill' => 'nullable|email',
        ]);

        $data = [
            'paypal' => $request->paypal,
            'payoneer' => $request->payoneer,
            'bitcoin' => $request->bitcoin,
            'neteller' => $request->neteller,
            'skrill' => $request->skrill,

        ];

        if (isset($request->id) && $request->id != '0') {
            AffiliateOption::where('id', $request->id)->update($data);
        } else {
            $data['user_id'] = auth()->user()->id;
            AffiliateOption::create($data);
        }

        return back()->with('success', 'Payout accounts Updated Successfuly!');
    }
}
