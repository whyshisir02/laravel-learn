<?php

// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
// use Illuminate\Http\Request;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         api: __DIR__.'/../routes/api.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware): void {
//         //
//     })
//     ->withExceptions(function (Exceptions $exceptions): void {
//         $exceptions->shouldRenderJsonWhen(
//             fn (Request $request) => $request->is('api/*'),

//         );
//     })->create();


    // bootstrap/app.php
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\CheckEmailDomain;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['role' => CheckUserRole::class]);
        $middleware->api(prepend:[\App\Http\Middleware\ForceJsonRes::class]);
        $middleware->alias(['email.domain' => CheckEmailDomain::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Intercept Passport / Auth failures and return structured JSON
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status_code' => 401,
                    'status'      => 'error',
                    'message'     => 'Unauthorized: Invalid or missing Passport access token.',
                ], 401);
            }
        });
    })->create();
