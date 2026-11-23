<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }

            $code    = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $headers = $e instanceof HttpExceptionInterface ? $e->getHeaders() : [];

            // Bug kode (non-HTTP) saat debug aktif: tampilkan halaman debug Laravel
            if (! $e instanceof HttpExceptionInterface && config('app.debug')) {
                return null;
            }

            $presets = [
                403 => ['Access denied', 'You don’t have access to this page.'],
                404 => ['Page not found', 'This page doesn’t exist.'],
                419 => ['Page expired', 'Your session expired. Go back and try again.'],
                429 => ['Too many requests', 'Too many requests. Wait a moment and try again.'],
                500 => ['Server error', 'Something went wrong on our end. Try again in a moment.'],
                503 => ['Under maintenance', 'The site is under maintenance. Check back soon.'],
            ];

            [$title, $message] = $presets[$code] ?? (
                $code >= 500
                    ? ['Server error', 'Something went wrong on our end. Try again in a moment.']
                    : ['Request error', 'We couldn’t process that request.']
            );

            return response()->view('components.error.part1', [
                'code'    => $code,
                'title'   => $title,
                'message' => $message,
            ], $code, $headers);
        });
    })->create();