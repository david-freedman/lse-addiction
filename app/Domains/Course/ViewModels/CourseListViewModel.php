<?php

namespace App\Domains\Course\ViewModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class CourseListViewModel
{
    public function __construct(
        private LengthAwarePaginator $courses
    ) {}

    public function courses(): LengthAwarePaginator
    {
        return $this->courses;
    }

    public function hasNoCourses(): bool
    {
        return $this->courses->isEmpty();
    }
}
