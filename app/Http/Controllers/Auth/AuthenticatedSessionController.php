<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false))->with('success', 'You have logged in successfully.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($user && config('services.ticket_system.support_domain') && config('services.ticket_system.api_key') && config('services.ticket_system.api_secret')) {
            try {
                Http::timeout(3)
                    ->withHeaders([
                        'X-API-Key' => config('services.ticket_system.api_key'),
                        'X-API-Secret' => config('services.ticket_system.api_secret'),
                    ])
                    ->post($this->supportLogoutUrl(), [
                        'email' => $user->email,
                    ]);
            } catch (\Throwable $exception) {
                // Avoid breaking logout when the remote ticket logout call fails.
            }
        }

        return redirect('/')->with('info', 'You have been logged out.');
    }

    protected function supportLogoutUrl(): string
    {
        $domain = trim(config('services.ticket_system.support_domain'), '/ ');
        $domain = preg_replace('#^https?://#', '', $domain);

        return 'https://' . $domain . '/api/auth/logout';
    }
}
