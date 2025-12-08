<?php

namespace App\Domains\Lesson\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Module\Models\Module;
use Illuminate\Support\Collection;

readonly class ModuleLessonsViewModel
{
    public function __construct(
        private Course $course,
        private Module $module,
    ) {}

    public function course(): Course
    {
        return $this->course;
    }

    public function module(): Module
    {
        return $this->module;
    }

    public function lessons(): Collection
    {
        return $this->module->lessons()->ordered()->get();
    }

    /**
     * @return LessonType[]
     */
    public function lessonTypes(): array
    {
        return LessonType::cases();
    }

    public function hasNoLessons(): bool
    {
        return $this->lessons()->isEmpty();
    }
}
