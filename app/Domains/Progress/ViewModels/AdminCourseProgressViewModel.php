<?php

namespace App\Domains\Progress\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentModuleProgress;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class AdminCourseProgressViewModel
{
    private Course $course;

    private int $enrolledCount;

    private int $completedCount;

    private int $inProgressCount;

    private int $notStartedCount;

    private float $averageProgress;

    private Collection $moduleStats;

    private LengthAwarePaginator $studentProgress;

    public function __construct(Course $course, int $perPage = 20)
    {
        $this->course = $course->load('modules');

        $query = StudentCourseProgress::where('course_id', $course->id);

        $this->enrolledCount = (clone $query)->count();
        $this->completedCount = (clone $query)->where('status', ProgressStatus::Completed)->count();
        $this->inProgressCount = (clone $query)->where('status', ProgressStatus::InProgress)->count();
        $this->notStartedCount = (clone $query)->where('status', ProgressStatus::NotStarted)->count();
        $this->averageProgress = (clone $query)->avg('progress_percentage') ?? 0;

        $this->moduleStats = $this->calculateModuleStats();

        $this->studentProgress = StudentCourseProgress::query()
            ->where('course_id', $course->id)
            ->with('student')
            ->orderByDesc('progress_percentage')
            ->paginate($perPage);
    }

    private function calculateModuleStats(): Collection
    {
        return $this->course->modules->map(function (Module $module) {
            $query = StudentModuleProgress::where('module_id', $module->id);

            $total = (clone $query)->count();
            $completed = (clone $query)->where('status', ProgressStatus::Completed)->count();
            $avgProgress = (clone $query)->avg('progress_percentage') ?? 0;

            return [
                'module' => $module,
                'total' => $total,
                'completed' => $completed,
                'avg_progress' => round($avgProgress, 1),
            ];
        });
    }

    public function course(): Course
    {
        return $this->course;
    }

    public function enrolledCount(): int
    {
        return $this->enrolledCount;
    }

    public function completedCount(): int
    {
        return $this->completedCount;
    }

    public function inProgressCount(): int
    {
        return $this->inProgressCount;
    }

    public function notStartedCount(): int
    {
        return $this->notStartedCount;
    }

    public function averageProgress(): float
    {
        return round($this->averageProgress, 1);
    }

    public function moduleStats(): Collection
    {
        return $this->moduleStats;
    }

    public function studentProgress(): LengthAwarePaginator
    {
        return $this->studentProgress;
    }

    public function hasStudentProgress(): bool
    {
        return $this->studentProgress->isNotEmpty();
    }

    public function hasModules(): bool
    {
        return $this->course->modules->isNotEmpty();
    }

    public function completionRate(): float
    {
        if ($this->enrolledCount === 0) {
            return 0;
        }

        return round(($this->completedCount / $this->enrolledCount) * 100, 1);
    }
}
