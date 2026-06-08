<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreReferrerUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('ref_url')) {
            $referer = $request->headers->get('referer');

            if ($referer && !str_contains($referer, $request->getHost())) {
                session(['ref_url' => $referer]);
            }
        }

        return $next($request);
    }
}
