<?php

namespace App\Domains\Quiz\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Enums\QuestionType;
use App\Domains\Quiz\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;

readonly class QuizQuestionsViewModel
{
    public function __construct(
        private Course $course,
        private Module $module,
        private Lesson $lesson,
    ) {}

    public function course(): Course
    {
        return $this->course;
    }

    public function module(): Module
    {
        return $this->module;
    }

    public function lesson(): Lesson
    {
        return $this->lesson;
    }

    public function quiz(): ?Quiz
    {
        return $this->lesson->quiz;
    }

    public function questions(): Collection
    {
        if (!$this->quiz()) {
            return new Collection();
        }

        return $this->quiz()->questions()->with('answers')->orderBy('order')->get();
    }

    public function questionTypes(): array
    {
        return QuestionType::cases();
    }

    public function totalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function questionsCount(): int
    {
        return $this->questions()->count();
    }
}
