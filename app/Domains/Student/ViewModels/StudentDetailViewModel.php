<?php

namespace App\Domains\Student\ViewModels;

use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\ActivityLog\Models\ActivityLog;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Student\Enums\ProfileFieldType;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Jenssegers\Agent\Agent;

readonly class StudentDetailViewModel
{
    private Student $student;

    private Collection $enrolledCourses;

    private Collection $availableCourses;

    private LengthAwarePaginator $loginHistory;

    private Collection $transactions;

    private Collection $activeDiscounts;

    private Collection $usedDiscounts;

    private Collection $certificates;

    private Collection $registeredWebinars;

    private Collection $availableWebinars;

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
            'courses.teacher',
        ]);

        $this->enrolledCourses = $this->student->courses;

        $this->availableCourses = Course::whereNotIn('id', $this->enrolledCourses->pluck('id'))
            ->orderBy('name')
            ->get();

        $this->loginHistory = ActivityLog::where('subject_type', ActivitySubject::Student)
            ->where('subject_id', $student->id)
            ->whereIn('activity_type', [
                ActivityType::StudentLoginSuccess,
                ActivityType::StudentLoginFailed,
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(3, ['*'], 'login_history_page');

        $this->transactions = Transaction::where('student_id', $student->id)
            ->with('purchasable')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $this->activeDiscounts = StudentCourseDiscount::forStudent($student->id)
            ->active()
            ->with(['course', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->usedDiscounts = StudentCourseDiscount::forStudent($student->id)
            ->used()
            ->with(['course', 'assignedBy'])
            ->orderBy('used_at', 'desc')
            ->limit(10)
            ->get();

        $this->certificates = Certificate::forStudent($student->id)
            ->withTrashed()
            ->with(['course.teacher'])
            ->orderBy('issued_at', 'desc')
            ->get();

        $this->registeredWebinars = $student->webinars()
            ->wherePivotNull('cancelled_at')
            ->with('teacher')
            ->orderBy('starts_at', 'desc')
            ->get();

        $registeredWebinarIds = $this->registeredWebinars->pluck('id');

        $this->availableWebinars = Webinar::where('status', WebinarStatus::Upcoming)
            ->whereNotIn('id', $registeredWebinarIds)
            ->with('teacher')
            ->orderBy('starts_at')
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

    public function loginHistory(): LengthAwarePaginator
    {
        return $this->loginHistory;
    }

    public function parseUserAgent(?string $userAgent): array
    {
        if (! $userAgent) {
            return ['os' => 'N/A', 'browser' => 'N/A', 'device' => 'N/A'];
        }

        $agent = new Agent;
        $agent->setUserAgent($userAgent);

        $device = match (true) {
            $agent->isDesktop() => 'Desktop',
            $agent->isMobile() => 'Mobile',
            $agent->isTablet() => 'Tablet',
            default => 'Unknown',
        };

        return [
            'os' => $agent->platform() ?: 'Unknown',
            'browser' => $agent->browser() ?: 'Unknown',
            'device' => $device,
        ];
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
        return $this->loginHistory->total() > 0;
    }

    public function hasTransactions(): bool
    {
        return $this->transactions->isNotEmpty();
    }

    public function lastLoginAt(): ?string
    {
        return $this->student->last_login_at?->format('d.m.Y H:i');
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

    public function activeDiscounts(): Collection
    {
        return $this->activeDiscounts;
    }

    public function usedDiscounts(): Collection
    {
        return $this->usedDiscounts;
    }

    public function hasActiveDiscounts(): bool
    {
        return $this->activeDiscounts->isNotEmpty();
    }

    public function hasUsedDiscounts(): bool
    {
        return $this->usedDiscounts->isNotEmpty();
    }

    public function coursesWithoutActiveDiscount(): Collection
    {
        $courseIdsWithDiscount = $this->activeDiscounts->pluck('course_id');

        return $this->availableCourses->reject(function ($course) use ($courseIdsWithDiscount) {
            return $courseIdsWithDiscount->contains($course->id);
        });
    }

    public function profileFields(): array
    {
        $values = $this->student->profileFieldValues()->with('profileField')->get();

        $result = [];
        foreach ($values as $value) {
            if ($value->profileField && $value->value) {
                $displayValue = $value->value;

                if ($value->profileField->type === ProfileFieldType::Select && $value->profileField->options) {
                    $displayValue = $value->profileField->options[$value->value] ?? $value->value;
                } elseif ($value->profileField->type === ProfileFieldType::Tags) {
                    $decoded = json_decode($value->value, true);
                    $displayValue = is_array($decoded) ? $decoded : [$value->value];
                }

                $result[$value->profileField->label] = $displayValue;
            }
        }

        return $result;
    }

    public function hasProfileFields(): bool
    {
        return ! empty($this->profileFields());
    }

    public function certificates(): Collection
    {
        return $this->certificates;
    }

    public function hasCertificates(): bool
    {
        return $this->certificates->isNotEmpty();
    }

    public function certificatesCount(): int
    {
        return $this->certificates->whereNull('deleted_at')->count();
    }

    public function coursesEligibleForManualCertificate(): Collection
    {
        $certifiedCourseIds = $this->certificates->pluck('course_id');

        return $this->enrolledCourses->reject(function ($course) use ($certifiedCourseIds) {
            return $certifiedCourseIds->contains($course->id);
        });
    }

    public function hasCoursesEligibleForManualCertificate(): bool
    {
        return $this->coursesEligibleForManualCertificate()->isNotEmpty();
    }

    public function registeredWebinars(): Collection
    {
        return $this->registeredWebinars;
    }

    public function availableWebinars(): Collection
    {
        return $this->availableWebinars;
    }

    public function hasRegisteredWebinars(): bool
    {
        return $this->registeredWebinars->isNotEmpty();
    }

    public function hasAvailableWebinars(): bool
    {
        return $this->availableWebinars->isNotEmpty();
    }

    public function registeredWebinarsCount(): int
    {
        return $this->registeredWebinars->count();
    }
}
