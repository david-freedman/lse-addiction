<?php

namespace App\Domains\Progress\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class AdminProgressDashboardViewModel
{
    private Collection $courseStats;

    private int $totalStudents;

    private int $activeStudents;

    private int $completedCourses;

    private float $averageCompletion;

    private int $totalQuizAttempts;

    private float $averageQuizScore;

    private float $quizPassRate;

    private Collection $courses;

    public function __construct(
        private string $period = 'all'
    ) {
        $this->courses = Course::orderBy('name')->get();
        $this->totalStudents = Student::count();

        $progressQuery = StudentCourseProgress::query();
        $quizQuery = StudentQuizAttempt::query();

        if ($this->period !== 'all') {
            $dateFrom = $this->getPeriodStartDate();
            $progressQuery->where('updated_at', '>=', $dateFrom);
            $quizQuery->where('created_at', '>=', $dateFrom);
        }

        $this->activeStudents = (clone $progressQuery)
            ->where('status', ProgressStatus::InProgress)
            ->distinct('student_id')
            ->count('student_id');

        $this->completedCourses = (clone $progressQuery)
            ->where('status', ProgressStatus::Completed)
            ->count();

        $this->averageCompletion = (clone $progressQuery)->avg('progress_percentage') ?? 0;

        $this->totalQuizAttempts = (clone $quizQuery)->count();
        $this->averageQuizScore = (clone $quizQuery)->avg('score') ?? 0;

        $totalWithResult = (clone $quizQuery)->whereNotNull('passed')->count();
        $passedCount = (clone $quizQuery)->where('passed', true)->count();
        $this->quizPassRate = $totalWithResult > 0 ? ($passedCount / $totalWithResult) * 100 : 0;

        $this->courseStats = $this->calculateCourseStats();
    }

    private function getPeriodStartDate(): \Carbon\Carbon
    {
        return match ($this->period) {
            'today' => now()->startOfDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subMonths(3),
            'year' => now()->subYear(),
            default => now()->subYears(10),
        };
    }

    private function calculateCourseStats(): Collection
    {
        return $this->courses->map(function (Course $course) {
            $query = StudentCourseProgress::where('course_id', $course->id);

            if ($this->period !== 'all') {
                $query->where('updated_at', '>=', $this->getPeriodStartDate());
            }

            $enrolled = (clone $query)->count();
            $completed = (clone $query)->where('status', ProgressStatus::Completed)->count();
            $inProgress = (clone $query)->where('status', ProgressStatus::InProgress)->count();
            $avgProgress = (clone $query)->avg('progress_percentage') ?? 0;

            return [
                'course' => $course,
                'enrolled' => $enrolled,
                'completed' => $completed,
                'in_progress' => $inProgress,
                'avg_progress' => round($avgProgress, 1),
            ];
        })->filter(fn ($stat) => $stat['enrolled'] > 0);
    }

    public function totalStudents(): int
    {
        return $this->totalStudents;
    }

    public function activeStudents(): int
    {
        return $this->activeStudents;
    }

    public function completedCourses(): int
    {
        return $this->completedCourses;
    }

    public function averageCompletion(): float
    {
        return round($this->averageCompletion, 1);
    }

    public function totalQuizAttempts(): int
    {
        return $this->totalQuizAttempts;
    }

    public function averageQuizScore(): float
    {
        return round($this->averageQuizScore, 1);
    }

    public function quizPassRate(): float
    {
        return round($this->quizPassRate, 1);
    }

    public function courseStats(): Collection
    {
        return $this->courseStats;
    }

    public function courses(): Collection
    {
        return $this->courses;
    }

    public function period(): string
    {
        return $this->period;
    }

    public function periods(): array
    {
        return [
            'today' => 'Сьогодні',
            'week' => 'Тиждень',
            'month' => 'Місяць',
            'quarter' => 'Квартал',
            'year' => 'Рік',
            'all' => 'Весь час',
        ];
    }

    public function hasCourseStats(): bool
    {
        return $this->courseStats->isNotEmpty();
    }
}
