<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Enums\CourseType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class CourseListViewModel
{
    public function __construct(
        private LengthAwarePaginator $courses,
        private ?CourseType $currentFilter = null
    ) {}

    public function courses(): LengthAwarePaginator
    {
        return $this->courses;
    }

    public function hasNoCourses(): bool
    {
        return $this->courses->isEmpty();
    }

    public function currentFilter(): ?CourseType
    {
        return $this->currentFilter;
    }

    public function isUpcomingFilter(): bool
    {
        return $this->currentFilter === CourseType::Upcoming;
    }

    public function isRecordedFilter(): bool
    {
        return $this->currentFilter === CourseType::Recorded;
    }

    public function isFreeFilter(): bool
    {
        return $this->currentFilter === CourseType::Free;
    }

    public function filterUrl(CourseType $type): string
    {
        return route('customer.courses.browse', ['filter' => $type->value]);
    }
}
