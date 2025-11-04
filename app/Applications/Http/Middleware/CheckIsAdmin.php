<?php

namespace App\Applications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if (!$user || $user->id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
