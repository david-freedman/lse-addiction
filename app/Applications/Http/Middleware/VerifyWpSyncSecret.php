<?php

namespace App\Applications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWpSyncSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.wp_sync.secret');

        if (empty($secret) || $request->header('X-LSE-Secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
