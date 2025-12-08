<?php

namespace App\Domains\Progress\ViewModels;

use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class AdminStudentProgressViewModel
{
    private Student $student;

    private Collection $courseProgress;

    private Collection $quizAttempts;

    private int $totalLessonsCompleted;

    private int $totalQuizzesPassed;

    private float $averageQuizScore;

    private Collection $recentActivity;

    public function __construct(Student $student)
    {
        $this->student = $student;

        $this->courseProgress = StudentCourseProgress::query()
            ->where('student_id', $student->id)
            ->with('course')
            ->orderByDesc('updated_at')
            ->get();

        $this->quizAttempts = StudentQuizAttempt::query()
            ->where('student_id', $student->id)
            ->with('quiz')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $this->totalLessonsCompleted = StudentLessonProgress::query()
            ->where('student_id', $student->id)
            ->whereNotNull('completed_at')
            ->count();

        $this->totalQuizzesPassed = StudentQuizAttempt::query()
            ->where('student_id', $student->id)
            ->where('passed', true)
            ->count();

        $this->averageQuizScore = StudentQuizAttempt::query()
            ->where('student_id', $student->id)
            ->whereNotNull('score')
            ->avg('score') ?? 0;

        $this->recentActivity = $this->getRecentActivity();
    }

    private function getRecentActivity(): Collection
    {
        $lessons = StudentLessonProgress::query()
            ->where('student_id', $this->student->id)
            ->with('lesson')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'type' => 'lesson',
                'name' => $item->lesson?->name ?? 'Урок',
                'status' => $item->status->label(),
                'date' => $item->updated_at,
            ]);

        $quizzes = StudentQuizAttempt::query()
            ->where('student_id', $this->student->id)
            ->with('quiz')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'type' => 'quiz',
                'name' => $item->quiz?->title ?? 'Тест',
                'status' => $item->passed ? 'Пройдено' : 'Не пройдено',
                'date' => $item->created_at,
            ]);

        return $lessons->concat($quizzes)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }

    public function student(): Student
    {
        return $this->student;
    }

    public function courseProgress(): Collection
    {
        return $this->courseProgress;
    }

    public function quizAttempts(): Collection
    {
        return $this->quizAttempts;
    }

    public function totalLessonsCompleted(): int
    {
        return $this->totalLessonsCompleted;
    }

    public function totalQuizzesPassed(): int
    {
        return $this->totalQuizzesPassed;
    }

    public function averageQuizScore(): float
    {
        return round($this->averageQuizScore, 1);
    }

    public function recentActivity(): Collection
    {
        return $this->recentActivity;
    }

    public function hasCourseProgress(): bool
    {
        return $this->courseProgress->isNotEmpty();
    }

    public function hasQuizAttempts(): bool
    {
        return $this->quizAttempts->isNotEmpty();
    }

    public function hasRecentActivity(): bool
    {
        return $this->recentActivity->isNotEmpty();
    }

    public function overallProgress(): float
    {
        if ($this->courseProgress->isEmpty()) {
            return 0;
        }

        return round($this->courseProgress->avg('progress_percentage'), 1);
    }
}
