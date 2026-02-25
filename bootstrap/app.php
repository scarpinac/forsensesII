<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Logging\StructuredLogger;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\LoadUserMenu::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\NotificacaoMiddleware::class,
            \App\Http\Middleware\LogRequests::class,
        ]);

        $middleware->alias([
            'login.as' => \App\Http\Middleware\CheckLoginAsPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $e) {
            StructuredLogger::logError($e, [
                'request_url' => request()->fullUrl(),
                'request_method' => request()->method(),
                'request_parameters' => request()->all()
            ]);
        });
    })
    ->create();
