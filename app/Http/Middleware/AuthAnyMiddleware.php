<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAnyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Prefer staff if authenticated
        if (Auth::guard('staff')->check()) {
            Auth::shouldUse('staff');
            return $next($request);
        }

        if (Auth::guard('web')->check()) {
            Auth::shouldUse('web');
            return $next($request);
        }

        // Not authenticated on any guard -> redirect/abort based on expectation
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(401);
        }

        return redirect()->guest(route('site.login'));
    }
}
