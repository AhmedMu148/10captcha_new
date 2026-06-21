<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceCmsApiScope
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $abilities = $request->attributes->get('cms_token_abilities', []);

        // Grant access if token has super-ability or the explicit matching scope
        if (in_array('cms.all', $abilities, true) || in_array($ability, $abilities, true)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
