<?php

namespace App\Domains\Homework\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Support\Collection;

final class SubmissionDetailViewModel
{
    public function __construct(
        public readonly HomeworkSubmission $submission,
    ) {}

    public function course(): Course
    {
        return $this->submission->homework->lesson->module->course;
    }

    public function module(): Module
    {
        return $this->submission->homework->lesson->module;
    }

    public function lesson(): Lesson
    {
        return $this->submission->homework->lesson;
    }

    public function homework()
    {
        return $this->submission->homework;
    }

    public function student()
    {
        return $this->submission->student;
    }

    public function previousAttempts(): Collection
    {
        return $this->submission->homework->submissions()
            ->where('student_id', $this->submission->student_id)
            ->where('id', '!=', $this->submission->id)
            ->orderBy('attempt_number', 'desc')
            ->get();
    }

    public function statuses(): array
    {
        return HomeworkSubmissionStatus::cases();
    }

    public function canReview(): bool
    {
        return $this->submission->status === HomeworkSubmissionStatus::Pending;
    }
}
