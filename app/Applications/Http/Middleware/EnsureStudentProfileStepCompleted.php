<?php

namespace App\Applications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentProfileStepCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $student = Auth::user();

        if ($student && ! $student->hasCompletedProfileStep()) {
            return redirect()->route('student.profile.show')
                ->with('warning', 'Для доступу до контенту необхідно заповнити анкетні дані у вашому профілі. Для цього перейдіть в редагування профілю, вкладка "Анкетні дані".');
        }

        return $next($request);
    }
}
