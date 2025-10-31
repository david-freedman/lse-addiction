<?php

namespace App\Applications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = Auth::user();

        if ($customer && !$customer->isFullyVerified()) {
            return redirect()->route('customer.complete-verification');
        }

        return $next($request);
    }
}
