<?php

namespace App\Domains\Student\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Data\StudentGroupFilterData;
use App\Domains\Student\Models\StudentGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

readonly class StudentGroupListViewModel
{
    private LengthAwarePaginator $groups;

    private Collection $courses;

    private StudentGroupFilterData $filters;

    public function __construct(
        StudentGroupFilterData $filters,
        int $perPage = 20,
        ?array $restrictToCourseIds = null
    ) {
        $this->filters = $filters;

        $coursesQuery = Course::orderBy('name');
        if ($restrictToCourseIds !== null) {
            $coursesQuery->whereIn('id', $restrictToCourseIds);
        }
        $this->courses = $coursesQuery->get();

        $query = StudentGroup::query()
            ->with(['course', 'creator'])
            ->withCount('students');

        if ($restrictToCourseIds !== null) {
            $query->whereIn('course_id', $restrictToCourseIds);
        }

        if ($filters->search) {
            $query->where('name', 'ilike', "%{$filters->search}%");
        }

        if ($filters->course_id) {
            $query->where('course_id', $filters->course_id);
        }

        $this->groups = $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function groups(): LengthAwarePaginator
    {
        return $this->groups;
    }

    public function courses(): Collection
    {
        return $this->courses;
    }

    public function filters(): StudentGroupFilterData
    {
        return $this->filters;
    }

    public function hasNoGroups(): bool
    {
        return $this->groups->isEmpty();
    }

    public function totalCount(): int
    {
        return $this->groups->total();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->course_id !== null;
    }
}
