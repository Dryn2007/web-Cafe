<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // TAMBAHKAN BAGIAN INI:
        $middleware->validateCsrfTokens(except: [
            'order', // Kita izinkan URL '/order' diakses tanpa token CSRF
            'order/*', // Jaga-jaga kalau ada sub-route
        ]);

    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
