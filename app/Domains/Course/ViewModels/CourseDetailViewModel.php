<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Customer\Models\Customer;

readonly class CourseDetailViewModel
{
    public function __construct(
        private Course $course,
        private ?Customer $customer = null
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

    public function coachName(): string
    {
        return $this->course->coach->name;
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
        return $this->course->customers()
            ->wherePivot('status', 'active')
            ->count();
    }

    public function isEnrolled(): bool
    {
        if (!$this->customer) {
            return false;
        }

        return $this->course->customers()
            ->where('customer_id', $this->customer->id)
            ->wherePivot('status', 'active')
            ->exists();
    }

    public function wasPurchased(): bool
    {
        if (!$this->customer) {
            return false;
        }

        return $this->customer->hasPurchasedCourse($this->course);
    }

    public function canEnroll(): bool
    {
        return $this->course->isPublished() && !$this->isEnrolled();
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
