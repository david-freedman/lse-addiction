<?php

use App\Applications\Http\Middleware\EnsureStudentIsVerified;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\EnsureUserIsVerified;
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
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            return route('student.login');
        });
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'verified.student' => EnsureStudentIsVerified::class,
            'verified.user' => EnsureUserIsVerified::class,
            'role' => CheckUserRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'student/payment/callback',
            'student/payment/return',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
