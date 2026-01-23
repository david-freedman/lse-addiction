<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;

readonly class AdminCourseDetailViewModel
{
    public function __construct(
        private Course $course,
    ) {}

    public function course(): Course
    {
        return $this->course->load([
            'modules' => fn ($q) => $q->ordered()->with([
                'lessons' => fn ($q) => $q->ordered(),
            ]),
            'teacher',
            'author',
            'tags',
            'students',
        ]);
    }

    public function name(): string
    {
        return $this->course->name;
    }

    public function description(): string
    {
        return $this->course->description;
    }

    public function price(): string
    {
        return number_format($this->course->price, 2, ',', ' ');
    }

    public function teacherName(): string
    {
        return $this->course->teacher?->full_name ?? 'Не вказано';
    }

    public function bannerUrl(): ?string
    {
        return $this->course->bannerUrl;
    }

    public function statusLabel(): string
    {
        return $this->course->status->label();
    }

    public function tags(): array
    {
        return $this->course->tags->pluck('name')->toArray();
    }

    public function enrolledCount(): int
    {
        return $this->course->students()
            ->wherePivot('status', 'active')
            ->count();
    }

    public function createdAt(): string
    {
        return $this->course->created_at->format('d.m.Y H:i');
    }

    public function tree(): array
    {
        return $this->course->modules->map(fn (Module $module) => [
            'id' => $module->id,
            'name' => $module->name,
            'order' => $module->order,
            'status' => $module->status,
            'has_final_test' => $module->hasFinalTest(),
            'lessons_count' => $module->lessons->count(),
            'lessons' => $module->lessons->map(fn (Lesson $lesson) => [
                'id' => $lesson->id,
                'name' => $lesson->name,
                'type' => $lesson->type,
                'status' => $lesson->status,
                'duration' => $lesson->formatted_duration,
                'is_final' => $lesson->is_final,
            ]),
        ])->toArray();
    }

    public function statistics(): array
    {
        return [
            'modules_count' => $this->course->modules->count(),
            'lessons_count' => $this->course->modules->sum(fn ($m) => $m->lessons->count()),
            'students_count' => $this->course->students()->count(),
        ];
    }
}
