<?php

namespace App\Applications\Http\Student\Dashboard\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();

        $allCourses = $student->courses()
            ->withPivot(['enrolled_at', 'status'])
            ->get();

        $activeCourses = $student->courses()
            ->wherePivot('status', 'active')
            ->withPivot(['enrolled_at', 'status'])
            ->with(['teacher', 'tags'])
            ->orderBy('course_student.enrolled_at', 'desc')
            ->limit(3)
            ->get();

        $upcomingCourses = $student->courses()
            ->where('type', 'upcoming')
            ->wherePivot('status', 'active')
            ->where('starts_at', '>', now())
            ->with(['teacher'])
            ->orderBy('starts_at', 'asc')
            ->limit(3)
            ->get();

        $totalCount = $allCourses->count();
        $activeCount = $allCourses->where('pivot.status', 'active')->count();
        $completedCount = $allCourses->where('pivot.status', 'completed')->count();

        return view('student.dashboard', [
            'student' => $student,
            'activeCourses' => $activeCourses,
            'upcomingCourses' => $upcomingCourses,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'completedCount' => $completedCount,
        ]);
    }
}
