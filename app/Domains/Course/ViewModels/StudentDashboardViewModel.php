<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Data\ModuleProgressData;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class StudentDashboardViewModel
{
    public function __construct(
        private ?Course $course,
        private Student $student
    ) {}

    public function hasCourse(): bool
    {
        return $this->course !== null;
    }

    public function courseName(): string
    {
        return $this->course?->name ?? '';
    }

    public function courseDescription(): string
    {
        return 'Керуйте своїми курсами та відстежуйте прогрес навчання';
    }

    public function progressPercentage(): int
    {
        if (!$this->course) {
            return 0;
        }

        $total = $this->totalLessons();
        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->lessonsCompleted() / $total) * 100);
    }

    public function lessonsCompleted(): int
    {
        if (!$this->course) {
            return 0;
        }

        $lessonIds = $this->course->modules
            ->flatMap(fn (Module $m) => $m->lessons->pluck('id'));

        return $this->student->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', ProgressStatus::Completed)
            ->count();
    }

    public function totalLessons(): int
    {
        if (!$this->course) {
            return 0;
        }

        return $this->course->modules->sum(fn (Module $m) => $m->lessons->count());
    }

    public function modules(): Collection
    {
        if (!$this->course) {
            return collect();
        }

        return $this->course->modules->map(fn (Module $module) => $this->buildModuleProgressData($module));
    }

    private function buildModuleProgressData(Module $module): ModuleProgressData
    {
        $moduleProgress = $this->student->moduleProgress()
            ->where('module_id', $module->id)
            ->first();

        $lessonIds = $module->lessons->pluck('id');
        $completedLessonIds = $this->student->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', ProgressStatus::Completed)
            ->pluck('lesson_id')
            ->toArray();

        $completedLessonsCount = count($completedLessonIds);
        $totalLessons = $module->lessons->count();
        $progressPercentage = $totalLessons > 0
            ? (int) round(($completedLessonsCount / $totalLessons) * 100)
            : 0;

        $isUnlocked = $module->isUnlocked($this->student);

        $recentLessons = $module->lessons
            ->filter(fn ($lesson) => !in_array($lesson->id, $completedLessonIds))
            ->take(2)
            ->map(fn ($lesson) => [
                'id' => $lesson->id,
                'name' => $lesson->name,
            ])
            ->toArray();

        return new ModuleProgressData(
            id: $module->id,
            courseId: $this->course->id,
            name: $module->name,
            description: $module->description,
            order: $module->order,
            progressPercentage: $progressPercentage,
            lessonsCompleted: $completedLessonsCount,
            totalLessons: $totalLessons,
            isUnlocked: $isUnlocked,
            unlockMessage: !$isUnlocked ? 'Завершіть попередні модулі, щоб розблокувати' : null,
            iconType: $this->determineIconType($module, $isUnlocked),
            recentLessons: $recentLessons,
        );
    }

    private function determineIconType(Module $module, bool $isUnlocked): string
    {
        if (!$isUnlocked) {
            return 'lock';
        }

        if ($module->has_final_test) {
            return 'quiz';
        }

        $primaryType = $module->lessons->first()?->type;

        return match ($primaryType) {
            LessonType::Quiz => 'quiz',
            LessonType::Video => 'video',
            default => 'video',
        };
    }
}
