<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Sanctum;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'cors' => App\Http\Middleware\CorsMiddleware::class,
            'auth.sanctum' => Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Configure auth middleware for API to return JSON instead of redirecting
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return null; // Don't redirect for API requests
            }

            return route('login');
        });

        // Override the default authentication exception handler for API requests
        $middleware->web(append: [
            App\Http\Middleware\CorsMiddleware::class,
        ]);

        $middleware->api(append: [
            App\Http\Middleware\CorsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Show detailed errors in development
        if (app()->environment('local', 'development')) {
            $exceptions->render(function (Throwable $e) {
                $request = request();
                if ($request && ($request->expectsJson() || $request->is('api/*'))) {
                    return response()->json([
                        'error' => true,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ], 500);
                }

                return null; // Let Laravel handle non-API requests normally
            });
        }
    })->create();
