<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only attempt auth if there's a Bearer token
        if ($request->bearerToken()) {
            Auth::shouldUse('sanctum');

            try {
                $user = Auth::user(); // Triggers token parsing and auth
            } catch (\Exception $e) {
                // Invalid token — ignore and continue as guest
            }
        }

        return $next($request);
    }
}
