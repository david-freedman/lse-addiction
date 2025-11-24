<?php

namespace App\Applications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $student = Auth::user();

        if ($student && ! $student->isFullyVerified()) {
            return redirect()->route('student.complete-verification');
        }

        return $next($request);
    }
}
