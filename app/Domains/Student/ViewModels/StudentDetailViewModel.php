<?php

namespace App\Domains\Student\ViewModels;

use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\ActivityLog\Models\ActivityLog;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

readonly class StudentDetailViewModel
{
    private Student $student;

    private Collection $enrolledCourses;

    private Collection $availableCourses;

    private Collection $teachers;

    private Collection $loginHistory;

    private Collection $transactions;

    public function __construct(Student $student)
    {
        $this->student = $student->load([
            'courses' => function ($query) {
                $query->withPivot([
                    'enrolled_at',
                    'status',
                    'teacher_id',
                    'individual_discount',
                    'lessons_completed',
                    'total_lessons',
                    'last_activity_at',
                    'notes',
                ]);
            },
            'courses.coach',
            'coupons',
        ]);

        $this->enrolledCourses = $this->student->courses;

        $this->availableCourses = Course::whereNotIn('id', $this->enrolledCourses->pluck('id'))
            ->orderBy('name')
            ->get();

        $this->teachers = User::teachers()->orderBy('name')->get();

        $this->loginHistory = ActivityLog::where('subject_type', \App\Domains\ActivityLog\Enums\ActivitySubject::Student)
            ->where('subject_id', $student->id)
            ->whereIn('activity_type', [
                ActivityType::StudentLoginSuccess,
                ActivityType::StudentLoginFailed,
            ])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $this->transactions = Transaction::where('student_id', $student->id)
            ->with('purchasable')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    public function student(): Student
    {
        return $this->student;
    }

    public function enrolledCourses(): Collection
    {
        return $this->enrolledCourses;
    }

    public function availableCourses(): Collection
    {
        return $this->availableCourses;
    }

    public function teachers(): Collection
    {
        return $this->teachers;
    }

    public function loginHistory(): Collection
    {
        return $this->loginHistory;
    }

    public function transactions(): Collection
    {
        return $this->transactions;
    }

    public function hasEnrolledCourses(): bool
    {
        return $this->enrolledCourses->isNotEmpty();
    }

    public function hasAvailableCourses(): bool
    {
        return $this->availableCourses->isNotEmpty();
    }

    public function hasLoginHistory(): bool
    {
        return $this->loginHistory->isNotEmpty();
    }

    public function hasTransactions(): bool
    {
        return $this->transactions->isNotEmpty();
    }

    public function lastLoginAt(): ?string
    {
        $lastLogin = $this->loginHistory->first();

        return $lastLogin?->created_at?->format('Y-m-d H:i:s');
    }

    public function enrolledCoursesCount(): int
    {
        return $this->enrolledCourses->count();
    }

    public function totalSpent(): float
    {
        return $this->transactions
            ->where('status', \App\Domains\Transaction\Enums\TransactionStatus::Completed)
            ->sum('amount');
    }
}
