<?php

namespace App\Domains\Module\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Data\LessonCardData;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class ModuleDetailViewModel
{
    public function __construct(
        private Module $module,
        private Course $course,
        private Student $student
    ) {}

    public function moduleId(): int
    {
        return $this->module->id;
    }

    public function moduleName(): string
    {
        return $this->module->name;
    }

    public function moduleDescription(): ?string
    {
        return $this->module->description;
    }

    public function moduleNumber(): int
    {
        return $this->module->order + 1;
    }

    public function progressPercentage(): int
    {
        $total = $this->totalLessons();
        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->lessonsCompleted() / $total) * 100);
    }

    public function lessonsCompleted(): int
    {
        $lessonIds = $this->module->lessons->pluck('id');

        return $this->student->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', ProgressStatus::Completed)
            ->count();
    }

    public function totalLessons(): int
    {
        return $this->module->lessons->count();
    }

    public function isUnlocked(): bool
    {
        return $this->module->isUnlocked($this->student);
    }

    public function unlockMessage(): ?string
    {
        if ($this->isUnlocked()) {
            return null;
        }

        return match ($this->module->unlock_rule) {
            ModuleUnlockRule::CompletePrevious => 'Завершіть попередній модуль, щоб розблокувати',
            ModuleUnlockRule::CompleteTest => 'Пройдіть тест попереднього модуля, щоб розблокувати',
            default => 'Модуль заблоковано',
        };
    }

    public function lessons(): Collection
    {
        return $this->module->lessons
            ->sortBy('order')
            ->map(function (Lesson $lesson) {
                $progress = $lesson->progress()
                    ->where('student_id', $this->student->id)
                    ->first();

                $isCompleted = $progress?->status === ProgressStatus::Completed;
                $iconType = $this->getLessonIconType($lesson->type);

                return new LessonCardData(
                    id: $lesson->id,
                    name: $lesson->name,
                    description: $lesson->description,
                    order: $lesson->order,
                    type: $lesson->type->value,
                    iconType: $lesson->is_final ? 'final_test' : $iconType,
                    duration: $lesson->formatted_duration,
                    isCompleted: $isCompleted,
                    url: route('student.lessons.show', [$this->course, $lesson]),
                    colorScheme: $lesson->is_final ? $this->getColorScheme('final_test') : $this->getColorScheme($iconType),
                    hasHomework: $lesson->homework !== null,
                    formattedDate: $lesson->formatted_date,
                    formattedTime: $lesson->formatted_time,
                    isFinal: $lesson->is_final,
                );
            })
            ->values();
    }

    private function getLessonIconType(LessonType $type): string
    {
        return match ($type) {
            LessonType::Video => 'video',
            LessonType::Quiz => 'quiz',
            LessonType::Text => 'text',
            LessonType::Dicom => 'dicom',
            LessonType::Survey => 'survey',
            LessonType::QaSession => 'qa_session',
        };
    }

    private function getColorScheme(string $iconType): array
    {
        return match ($iconType) {
            'video' => [
                'icon_bg' => 'bg-teal-100',
                'icon_color' => 'text-teal-600',
                'progress_color' => 'teal',
                'button_bg' => 'bg-teal-500 hover:bg-teal-600',
                'border_color' => 'border-teal-500',
            ],
            'quiz' => [
                'icon_bg' => 'bg-purple-100',
                'icon_color' => 'text-purple-600',
                'progress_color' => 'purple',
                'button_bg' => 'bg-purple-500 hover:bg-purple-600',
                'border_color' => 'border-purple-500',
            ],
            'text' => [
                'icon_bg' => 'bg-blue-100',
                'icon_color' => 'text-blue-600',
                'progress_color' => 'blue',
                'button_bg' => 'bg-blue-500 hover:bg-blue-600',
                'border_color' => 'border-blue-500',
            ],
            'dicom' => [
                'icon_bg' => 'bg-rose-100',
                'icon_color' => 'text-rose-600',
                'progress_color' => 'rose',
                'button_bg' => 'bg-rose-500 hover:bg-rose-600',
                'border_color' => 'border-rose-500',
            ],
            'survey' => [
                'icon_bg' => 'bg-orange-100',
                'icon_color' => 'text-orange-600',
                'progress_color' => 'orange',
                'button_bg' => 'bg-orange-500 hover:bg-orange-600',
                'border_color' => 'border-orange-500',
            ],
            'qa_session' => [
                'icon_bg' => 'bg-cyan-100',
                'icon_color' => 'text-cyan-600',
                'progress_color' => 'cyan',
                'button_bg' => 'bg-cyan-500 hover:bg-cyan-600',
                'border_color' => 'border-cyan-500',
            ],
            'final_test' => [
                'icon_bg' => 'bg-amber-100',
                'icon_color' => 'text-amber-600',
                'progress_color' => 'amber',
                'button_bg' => 'bg-amber-500 hover:bg-amber-600',
                'border_color' => 'border-amber-500',
            ],
            default => [
                'icon_bg' => 'bg-gray-100',
                'icon_color' => 'text-gray-600',
                'progress_color' => 'gray',
                'button_bg' => 'bg-gray-500 hover:bg-gray-600',
                'border_color' => 'border-gray-500',
            ],
        };
    }

    public function continueUrl(): ?string
    {
        $firstIncomplete = $this->getFirstIncompleteLesson();

        if (!$firstIncomplete) {
            return null;
        }

        return route('student.lessons.show', [$this->course, $firstIncomplete]);
    }

    public function hasContinueLesson(): bool
    {
        return $this->getFirstIncompleteLesson() !== null;
    }

    private function getFirstIncompleteLesson(): ?Lesson
    {
        $completedIds = $this->student->lessonProgress()
            ->whereIn('lesson_id', $this->module->lessons->pluck('id'))
            ->where('status', ProgressStatus::Completed)
            ->pluck('lesson_id');

        return $this->module->lessons
            ->sortBy('order')
            ->first(fn (Lesson $lesson) => !$completedIds->contains($lesson->id));
    }

    public function courseName(): string
    {
        return $this->course->name;
    }

    public function courseId(): int
    {
        return $this->course->id;
    }

    public function courseProgressPercent(): int
    {
        $progress = $this->student->courseProgress()
            ->where('course_id', $this->course->id)
            ->first();

        return $progress ? (int) $progress->progress_percentage : 0;
    }

    public function backToCourseUrl(): string
    {
        return route('student.courses.show', $this->course);
    }

    public function modules(): Collection
    {
        return $this->course->modules()
            ->active()
            ->ordered()
            ->with(['lessons' => function ($query) {
                $query->published()->ordered()->with('homework');
            }])
            ->get()
            ->map(function (Module $module) {
                $lessonsWithProgress = $module->lessons->map(function (Lesson $lesson) {
                    $progress = $lesson->progress()
                        ->where('student_id', $this->student->id)
                        ->first();

                    return [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'type' => $lesson->type,
                        'typeIcon' => $this->getLessonTypeIconForSidebar($lesson->type),
                        'duration' => $lesson->formatted_duration,
                        'isCompleted' => $progress?->status === ProgressStatus::Completed,
                        'isCurrent' => false,
                        'url' => route('student.lessons.show', [$this->course, $lesson]),
                        'hasHomework' => $lesson->homework !== null,
                    ];
                });

                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'order' => $module->order,
                    'isCurrent' => $module->id === $this->module->id,
                    'isUnlocked' => $module->isUnlocked($this->student),
                    'lessons' => $lessonsWithProgress,
                ];
            });
    }

    private function getLessonTypeIconForSidebar(LessonType $type): string
    {
        return match ($type) {
            LessonType::Video => 'play',
            LessonType::Quiz => 'question',
            LessonType::Text => 'document',
            LessonType::Dicom => 'medical',
            LessonType::Survey => 'chart',
            LessonType::QaSession => 'chat',
        };
    }
}
