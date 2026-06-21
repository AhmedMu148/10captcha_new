<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\AffiliateRegisterRelation;
use App\Models\UserDetail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 1,
            'hash'     => md5(rand(0, 1000)),
            'ref_url'  => session('ref_url', 'NA'),
            'date'     => now(),
        ]);

        $affiliateToken = Cookie::get('affiliate_token');

        if ($affiliateToken) {

            $affiliate = Affiliate::where('hash', $affiliateToken)
                ->where('status', 'Approve')
                ->first();

            if ($affiliate) {

                AffiliateRegisterRelation::create([
                    'aff_id' => $affiliate->id,
                    'user_id' => $user->id,
                ]);
            }
        }
        // Detect country from IP
        $country = $this->resolveCountry($request);

        // Create user_details record
        UserDetail::create([
            'user_id' => $user->id,
            'country' => $country,
            'ref_url' => session('ref_url', ''),
        ]);

        // Clear ref_url from session after use
        session()->forget('ref_url');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('success', 'Account created successfully. Welcome!');
    }

    private function resolveCountry(Request $request): string
    {
        $ip = $request->header('HTTP_CLIENT_IP')
            ?? $request->header('HTTP_X_FORWARDED_FOR')
            ?? $request->ip();

        // Skip local/private IPs
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return '';
        }

        try {
            $response = @file_get_contents('http://ip-api.com/json/' . urlencode($ip) . '?fields=status,countryCode');
            if ($response) {
                $data = json_decode($response, true);
                if ($data && $data['status'] === 'success') {
                    return $data['countryCode'];
                }
            }
        } catch (\Throwable) {
            // silently fail
        }

        return '';
    }
}
