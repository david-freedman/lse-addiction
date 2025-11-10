<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

readonly class MyCoursesViewModel
{
    private Collection $courses;

    public function __construct(Customer $customer)
    {
        $this->courses = $customer->courses()
            ->wherePivot('status', 'active')
            ->withPivot(['enrolled_at', 'status'])
            ->orderBy('course_customer.enrolled_at', 'desc')
            ->get();
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
}
