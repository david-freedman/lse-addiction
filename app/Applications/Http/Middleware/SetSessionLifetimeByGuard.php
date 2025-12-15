<?php

declare(strict_types=1);

namespace App\Applications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetSessionLifetimeByGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $lifetime = $this->getSessionLifetime($request);
        Config::set('session.lifetime', $lifetime);

        return $next($request);
    }

    private function getSessionLifetime(Request $request): int
    {
        if ($request->is('admin', 'admin/*')) {
            return (int) env('SESSION_LIFETIME_ADMIN', 10080);
        }

        return (int) env('SESSION_LIFETIME_STUDENT', 20160);
    }
}
