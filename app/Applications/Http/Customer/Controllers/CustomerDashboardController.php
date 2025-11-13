<?php

namespace App\Applications\Http\Customer\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerDashboardController
{
    public function index(Request $request): View
    {
        $customer = $request->user();

        $allCourses = $customer->courses()
            ->withPivot(['enrolled_at', 'status'])
            ->get();

        $activeCourses = $customer->courses()
            ->wherePivot('status', 'active')
            ->withPivot(['enrolled_at', 'status'])
            ->with(['coach', 'tags'])
            ->orderBy('course_customer.enrolled_at', 'desc')
            ->limit(3)
            ->get();

        $upcomingCourses = $customer->courses()
            ->where('type', 'upcoming')
            ->wherePivot('status', 'active')
            ->where('starts_at', '>', now())
            ->with(['coach'])
            ->orderBy('starts_at', 'asc')
            ->limit(3)
            ->get();

        $totalCount = $allCourses->count();
        $activeCount = $allCourses->where('pivot.status', 'active')->count();
        $completedCount = $allCourses->where('pivot.status', 'completed')->count();

        return view('customer.dashboard', [
            'customer' => $customer,
            'activeCourses' => $activeCourses,
            'upcomingCourses' => $upcomingCourses,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'completedCount' => $completedCount,
        ]);
    }
}
