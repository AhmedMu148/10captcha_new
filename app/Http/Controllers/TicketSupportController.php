<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketSupportController extends Controller
{
    public function sso(Request $request): RedirectResponse
    {
        $user = $request->user();
        $supportDomain = trim(config('services.ticket_system.support_domain', ''));
        $secret = config('services.ticket_system.sso_secret');

        if (! $user || ! $supportDomain || ! $secret) {
            return redirect()->route('dashboard')->with('error', 'Support SSO is not configured.');
        }

        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + 300,
            'jti' => (string) Str::uuid(),
        ];

        $redirectUrl = trim((string) $request->query('redirect_url', ''));
        if ($redirectUrl !== '') {
            $payload['redirect_url'] = $redirectUrl;
        }

        $token = $this->encodeJwt($payload, $secret);
        $callbackUrl = $this->buildSupportUrl($supportDomain, '/auth/callback', ['token' => $token]);

        return redirect()->away($callbackUrl);
    }

    public function handleIntended(Request $request): RedirectResponse
    {
        return $this->sso($request);
    }

    protected function buildSupportUrl(string $supportDomain, string $path, array $query = []): string
    {
        $supportDomain = preg_replace('#^https?://#', '', trim($supportDomain, '/ '));
        $url = 'https://' . $supportDomain . '/' . ltrim($path, '/');

        if (! empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    protected function encodeJwt(array $payload, string $secret): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];

        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $secret, true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
