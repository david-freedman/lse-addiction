<?php

namespace App\Domains\Dashboard\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Student\Models\Student;
use App\Domains\Teacher\Models\Teacher;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class AdminDashboardViewModel
{
    private ?array $teacherCourseIds;

    public function __construct(
        private User $user
    ) {
        $this->teacherCourseIds = $this->user->isTeacher()
            ? $this->user->getTeacherCourseIds()
            : null;
    }

    public function isAdmin(): bool
    {
        return $this->user->isAdmin();
    }

    public function isTeacher(): bool
    {
        return $this->user->isTeacher();
    }

    public function hasNoCourses(): bool
    {
        return $this->isTeacher() && empty($this->teacherCourseIds);
    }

    public function totalCourses(): int
    {
        return Course::count();
    }

    public function totalTeachers(): int
    {
        return Teacher::count();
    }

    public function totalStudents(): int
    {
        return Student::count();
    }

    public function totalRevenue(): float
    {
        return (float) Transaction::where('status', 'completed')->sum('amount');
    }

    public function revenueChange(): float
    {
        $thisMonthRevenue = Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $lastMonthRevenue = Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        if ($lastMonthRevenue <= 0) {
            return 0;
        }

        return round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
    }

    public function recentTransactions(): Collection
    {
        return Transaction::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function monthlyRevenue(): Collection
    {
        return Transaction::where('status', 'completed')
            ->select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function enrollmentsByMonth(): Collection
    {
        return DB::table('course_student')
            ->select(
                DB::raw("TO_CHAR(enrolled_at, 'YYYY-MM') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('enrolled_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function myCourseCount(): int
    {
        if (!$this->teacherCourseIds) {
            return 0;
        }

        return count($this->teacherCourseIds);
    }

    public function myStudentCount(): int
    {
        if (!$this->teacherCourseIds || empty($this->teacherCourseIds)) {
            return 0;
        }

        return DB::table('course_student')
            ->whereIn('course_id', $this->teacherCourseIds)
            ->distinct('student_id')
            ->count('student_id');
    }

    public function averageProgress(): float
    {
        if (!$this->teacherCourseIds || empty($this->teacherCourseIds)) {
            return 0;
        }

        $avg = StudentCourseProgress::whereIn('course_id', $this->teacherCourseIds)
            ->avg('progress_percentage');

        return round((float) $avg, 1);
    }

    public function pendingHomeworkCount(): int
    {
        if (!$this->teacherCourseIds || empty($this->teacherCourseIds)) {
            return 0;
        }

        return HomeworkSubmission::where('status', HomeworkSubmissionStatus::Pending)
            ->whereHas('homework.lesson.module', function ($query) {
                $query->whereIn('course_id', $this->teacherCourseIds);
            })
            ->count();
    }

    public function myEnrollmentsByMonth(): Collection
    {
        if (!$this->teacherCourseIds || empty($this->teacherCourseIds)) {
            return collect();
        }

        return DB::table('course_student')
            ->select(
                DB::raw("TO_CHAR(enrolled_at, 'YYYY-MM') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereIn('course_id', $this->teacherCourseIds)
            ->where('enrolled_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function progressTrendByMonth(): Collection
    {
        if (!$this->teacherCourseIds || empty($this->teacherCourseIds)) {
            return collect();
        }

        return StudentCourseProgress::whereIn('course_id', $this->teacherCourseIds)
            ->select(
                DB::raw("TO_CHAR(updated_at, 'YYYY-MM') as month"),
                DB::raw('AVG(progress_percentage) as average')
            )
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->average = round((float) $item->average, 1);
                return $item;
            });
    }

    public function pendingHomeworkList(): Collection
    {
        if (!$this->teacherCourseIds || empty($this->teacherCourseIds)) {
            return collect();
        }

        return HomeworkSubmission::with(['student', 'homework.lesson.module.course'])
            ->where('status', HomeworkSubmissionStatus::Pending)
            ->whereHas('homework.lesson.module', function ($query) {
                $query->whereIn('course_id', $this->teacherCourseIds);
            })
            ->latest('submitted_at')
            ->take(5)
            ->get();
    }

    public function upcomingWebinars(): Collection
    {
        $teacherId = $this->user->teacherProfile?->id;

        if (!$teacherId) {
            return collect();
        }

        return Webinar::query()
            ->where('teacher_id', $teacherId)
            ->whereIn('status', [WebinarStatus::Upcoming, WebinarStatus::Live])
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->with('teacher')
            ->take(3)
            ->get();
    }
}
