<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;

readonly class CourseDetailViewModel
{
    public function __construct(
        private Course $course,
        private ?Student $student = null
    ) {}

    public function id(): int
    {
        return $this->course->id;
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

    public function priceRaw(): float
    {
        return (float) $this->course->price;
    }

    public function teacherName(): string
    {
        return $this->course->teacher?->full_name ?? 'Не вказано';
    }

    public function bannerUrl(): ?string
    {
        return $this->course->bannerUrl;
    }

    public function status(): string
    {
        return $this->course->status;
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

    public function isEnrolled(): bool
    {
        if (! $this->student) {
            return false;
        }

        return $this->course->students()
            ->where('student_id', $this->student->id)
            ->wherePivot('status', 'active')
            ->exists();
    }

    public function wasPurchased(): bool
    {
        if (! $this->student) {
            return false;
        }

        return $this->student->hasPurchasedCourse($this->course);
    }

    public function canEnroll(): bool
    {
        return $this->course->isPublished() && ! $this->isEnrolled();
    }

    public function isPublished(): bool
    {
        return $this->course->isPublished();
    }

    public function createdAt(): string
    {
        return $this->course->created_at->format('d.m.Y H:i');
    }
}
