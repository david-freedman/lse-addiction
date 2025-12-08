<?php

namespace App\Domains\Lesson\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class LessonDetailViewModel
{
    public function __construct(
        private Lesson $lesson,
        private Course $course,
        private Student $student
    ) {}

    public function lessonId(): int
    {
        return $this->lesson->id;
    }

    public function lessonName(): string
    {
        return $this->lesson->name;
    }

    public function moduleName(): string
    {
        return $this->lesson->module->name;
    }

    public function moduleNumber(): int
    {
        return $this->lesson->module->order;
    }

    public function lessonNumber(): int
    {
        return $this->lesson->order;
    }

    public function totalLessonsInModule(): int
    {
        return $this->lesson->module->lessons()->published()->count();
    }

    public function duration(): string
    {
        if (!$this->lesson->duration_minutes) {
            return '';
        }

        $hours = intdiv($this->lesson->duration_minutes, 60);
        $minutes = $this->lesson->duration_minutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function durationFormatted(): string
    {
        if (! $this->lesson->duration_minutes) {
            return '';
        }

        return $this->duration();
    }

    public function description(): string
    {
        return $this->lesson->description ?? '';
    }

    public function content(): string
    {
        return $this->lesson->content ?? '';
    }

    public function videoUrl(): ?string
    {
        return $this->lesson->video_url;
    }

    public function isYouTube(): bool
    {
        $url = $this->lesson->video_url;

        if (! $url) {
            return false;
        }

        return str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be');
    }

    public function isVimeo(): bool
    {
        $url = $this->lesson->video_url;

        if (! $url) {
            return false;
        }

        return str_contains($url, 'vimeo.com');
    }

    public function embedUrl(): ?string
    {
        $url = $this->lesson->video_url;

        if (! $url) {
            return null;
        }

        if ($this->isYouTube()) {
            return $this->getYouTubeEmbedUrl($url);
        }

        if ($this->isVimeo()) {
            return $this->getVimeoEmbedUrl($url);
        }

        return $url;
    }

    private function getYouTubeEmbedUrl(string $url): string
    {
        $videoId = null;

        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $url;
    }

    private function getVimeoEmbedUrl(string $url): string
    {
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}";
        }

        return $url;
    }

    public function courseProgressPercent(): int
    {
        $progress = $this->student->courseProgress()
            ->where('course_id', $this->course->id)
            ->first();

        return $progress ? (int) $progress->progress_percentage : 0;
    }

    public function isCompleted(): bool
    {
        $progress = $this->lesson->progress()
            ->where('student_id', $this->student->id)
            ->first();

        return $progress?->status === ProgressStatus::Completed;
    }

    public function previousLesson(): ?Lesson
    {
        $currentModule = $this->lesson->module;

        $previousInModule = $currentModule->lessons()
            ->published()
            ->where('order', '<', $this->lesson->order)
            ->orderByDesc('order')
            ->first();

        if ($previousInModule) {
            return $previousInModule;
        }

        $previousModule = $this->course->modules()
            ->active()
            ->where('order', '<', $currentModule->order)
            ->orderByDesc('order')
            ->first();

        if ($previousModule) {
            return $previousModule->lessons()
                ->published()
                ->orderByDesc('order')
                ->first();
        }

        return null;
    }

    public function nextLesson(): ?Lesson
    {
        $currentModule = $this->lesson->module;

        $nextInModule = $currentModule->lessons()
            ->published()
            ->where('order', '>', $this->lesson->order)
            ->orderBy('order')
            ->first();

        if ($nextInModule) {
            return $nextInModule;
        }

        $nextModule = $this->course->modules()
            ->active()
            ->where('order', '>', $currentModule->order)
            ->orderBy('order')
            ->first();

        if ($nextModule) {
            return $nextModule->lessons()
                ->published()
                ->orderBy('order')
                ->first();
        }

        return null;
    }

    public function canNavigateToPrevious(): bool
    {
        return $this->previousLesson() !== null;
    }

    public function canNavigateToNext(): bool
    {
        return $this->nextLesson() !== null;
    }

    public function previousLessonUrl(): ?string
    {
        $previous = $this->previousLesson();

        if (! $previous) {
            return null;
        }

        return route('student.lessons.show', [$this->course, $previous]);
    }

    public function nextLessonUrl(): ?string
    {
        $next = $this->nextLesson();

        if (! $next) {
            return null;
        }

        return route('student.lessons.show', [$this->course, $next]);
    }

    public function modules(): Collection
    {
        return $this->course->modules()
            ->active()
            ->ordered()
            ->with(['lessons' => function ($query) {
                $query->published()->ordered();
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
                        'typeIcon' => $this->getLessonTypeIcon($lesson->type),
                        'duration' => $lesson->formatted_duration,
                        'isCompleted' => $progress?->status === ProgressStatus::Completed,
                        'isCurrent' => $lesson->id === $this->lesson->id,
                        'url' => route('student.lessons.show', [$this->course, $lesson]),
                    ];
                });

                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'order' => $module->order,
                    'lessons' => $lessonsWithProgress,
                ];
            });
    }

    private function getLessonTypeIcon(LessonType $type): string
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

    public function courseName(): string
    {
        return $this->course->name;
    }

    public function courseId(): int
    {
        return $this->course->id;
    }

    public function teacherName(): string
    {
        return $this->course->teacher?->full_name ?? '';
    }

    public function backToCourseUrl(): string
    {
        return route('student.my-courses');
    }

    public function lessonType(): LessonType
    {
        return $this->lesson->type;
    }

    public function isVideo(): bool
    {
        return $this->lesson->type === LessonType::Video;
    }

    public function isDownloadable(): bool
    {
        return $this->lesson->is_downloadable;
    }
}
