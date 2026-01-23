<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Data\LessonProgressData;
use App\Domains\Progress\Data\ModuleProgressData;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class CourseProgressViewModel
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

    public function courseSlug(): string
    {
        return $this->course?->slug ?? '';
    }

    public function courseDescription(): string
    {
        return $this->course?->description_short ?? $this->course?->description ?? '';
    }

    public function teacherName(): ?string
    {
        return $this->course?->teacher?->full_name;
    }

    public function totalModules(): int
    {
        return $this->course?->modules->count() ?? 0;
    }

    public function bannerUrl(): ?string
    {
        return $this->course?->banner_url;
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

        $lessons = $module->lessons
            ->map(fn ($lesson) => new LessonProgressData(
                id: $lesson->id,
                name: $lesson->name,
                type: $lesson->type->value,
                isCompleted: in_array($lesson->id, $completedLessonIds),
                duration: $lesson->formattedDuration,
                order: $lesson->order,
            ))
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
            lessons: $lessons,
        );
    }

    private function determineIconType(Module $module, bool $isUnlocked): string
    {
        if (!$isUnlocked) {
            return 'lock';
        }

        if ($module->hasFinalTest()) {
            return 'quiz';
        }

        if ($module->lessons->isEmpty()) {
            return 'video';
        }

        $typeCounts = $module->lessons->countBy(fn ($lesson) => $lesson->type->value);
        $maxCount = $typeCounts->max();
        $dominantTypes = $typeCounts->filter(fn ($count) => $count === $maxCount)->keys();

        $primaryType = $module->lessons
            ->sortBy('order')
            ->first(fn ($lesson) => $dominantTypes->contains($lesson->type->value))
            ?->type;

        return match ($primaryType) {
            LessonType::Quiz => 'quiz',
            LessonType::Video => 'video',
            LessonType::Text => 'text',
            LessonType::Dicom => 'dicom',
            LessonType::Survey => 'survey',
            LessonType::QaSession => 'qa_session',
            default => 'video',
        };
    }
}
