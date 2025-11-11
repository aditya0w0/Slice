<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register application security headers middleware so baseline
        // security headers are present on all web responses. Tweak or
        // move this to a group if you prefer it only for `web` routes.
    // Append to the "web" middleware group so sessions and CSRF are
    // already available when SecurityHeaders runs.
    $middleware->appendToGroup('web', \App\Http\Middleware\SecurityHeaders::class);

    // Prevent back button exploits - stops cached page access after logout
    $middleware->appendToGroup('web', \App\Http\Middleware\PreventBackHistory::class);

    // Session security - prevents hijacking and validates session integrity
    $middleware->appendToGroup('web', \App\Http\Middleware\SessionSecurity::class);

    // Register admin middleware
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
