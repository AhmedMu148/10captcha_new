<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AffiliateTracking
{
    public function handle(Request $request, Closure $next): Response
    {
        $affiliateToken = $this->getAffiliateToken($request);

        if ($affiliateToken) {
            Cookie::queue(
                'affiliate_token',
                $affiliateToken,
                60 * 24 * 30
            );
        }

        return $next($request);
    }

    private function getAffiliateToken(Request $request): ?string
    {
        // page.token
        if (preg_match('/\.([a-zA-Z0-9_-]+)$/', $request->path(), $matches)) {
            return trim($matches[1]);
        }

        // ?r=token
        if ($request->filled('r')) {
            return trim($request->query('r'));
        }

        // ?from=token
        if ($request->filled('from')) {
            return trim($request->query('from'));
        }

        return null;
    }
}
