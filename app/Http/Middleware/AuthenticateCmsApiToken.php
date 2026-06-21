<?php

namespace App\Http\Middleware;

use App\Models\CmsApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCmsApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Resolve token from Authorization header or custom X-CMS-Token header
        $token = $request->bearerToken();
        if (!$token) {
            $token = $request->header('X-CMS-Token');
        }

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // 2. Check against static ENV fallback configuration
        $staticToken = config('services.cms.api_token');
        if (filled($staticToken) && $token === $staticToken) {
            $request->attributes->set('cms_token_abilities', ['cms.all']);
            return $next($request);
        }

        // 3. Search token in database using SHA-256 hash
        $hash = hash('sha256', $token);
        $tokenRecord = CmsApiToken::where('token_hash', $hash)
            ->where('is_active', true)
            ->whereNull('revoked_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // 4. Update tracking metrics
        $tokenRecord->update([
            'last_used_at' => now(),
            'last_used_ip' => $request->ip(),
            'last_used_user_agent' => $request->userAgent(),
        ]);

        // 5. Store abilities in request attributes
        $request->attributes->set('cms_token_abilities', $tokenRecord->abilities ?? []);

        return $next($request);
    }
}
