<?php

namespace App\Applications\Http\Admin\Dashboard\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Teacher\Models\Teacher;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(): View
    {
        $totalCourses = Course::count();
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $verifiedStudents = Student::whereNotNull('email_verified_at')->count();

        $totalRevenue = Transaction::where('status', 'completed')
            ->sum('amount');

        $thisMonthRevenue = Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $lastMonthRevenue = Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('amount');

        $revenueChange = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $recentTransactions = Transaction::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        $monthlyRevenue = Transaction::where('status', 'completed')
            ->select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $enrollmentsByMonth = DB::table('course_student')
            ->select(
                DB::raw("TO_CHAR(enrolled_at, 'YYYY-MM') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('enrolled_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalTeachers',
            'totalStudents',
            'verifiedStudents',
            'totalRevenue',
            'revenueChange',
            'recentTransactions',
            'monthlyRevenue',
            'enrollmentsByMonth'
        ));
    }
}
