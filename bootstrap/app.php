<?php

use App\Http\Middleware\EnsureEmailVerified;
use App\Http\Middleware\EnsureTokenIsNotExpired;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Http\Middleware\HandleCors;
use Illuminate\Routing\Middleware\ThrottleRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->alias([
            'verified.api' => EnsureEmailVerified::class,
            'token.expired' => EnsureTokenIsNotExpired::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            return null;
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Unauthenticated (401)
        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
        });

        // Not Found (404 Model)
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        });

        // Not Found (404 Route)
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint not found'
            ], 404);
        });

        // Forbidden (403)
        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden'
                ], 403);
            }
        });

        // Too Many Requests (Throttle - 429)
        $exceptions->render(function (
            ThrottleRequestsException $e,
            Request $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many attempts. Please try again later.'
                ], 429);
            }
        });
    })->create();
