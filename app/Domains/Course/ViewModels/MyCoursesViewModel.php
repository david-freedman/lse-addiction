<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

readonly class MyCoursesViewModel
{
    private Collection $courses;
    private Collection $allCourses;
    private ?string $currentStatus;

    public function __construct(Customer $customer, ?string $status = null)
    {
        $this->currentStatus = $status;

        $this->allCourses = $customer->courses()
            ->withPivot(['enrolled_at', 'status'])
            ->with(['coach', 'tags'])
            ->orderBy('course_customer.enrolled_at', 'desc')
            ->get();

        $query = $customer->courses()
            ->withPivot(['enrolled_at', 'status'])
            ->with(['coach', 'tags'])
            ->orderBy('course_customer.enrolled_at', 'desc');

        if ($status) {
            $query->wherePivot('status', $status);
        }

        $this->courses = $query->get();
    }

    public function courses(): Collection
    {
        return $this->courses;
    }

    public function hasCourses(): bool
    {
        return $this->courses->isNotEmpty();
    }

    public function hasNoCourses(): bool
    {
        return $this->courses->isEmpty();
    }

    public function coursesCount(): int
    {
        return $this->courses->count();
    }

    public function totalCount(): int
    {
        return $this->allCourses->count();
    }

    public function activeCount(): int
    {
        return $this->allCourses->where('pivot.status', 'active')->count();
    }

    public function completedCount(): int
    {
        return $this->allCourses->where('pivot.status', 'completed')->count();
    }

    public function currentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function isFilteredBy(string $status): bool
    {
        return $this->currentStatus === $status;
    }

    public function isShowingAll(): bool
    {
        return $this->currentStatus === null;
    }
}
