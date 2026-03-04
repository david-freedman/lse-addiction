<?php

namespace App\Domains\Student\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Data\StudentFilterData;
use App\Domains\Student\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

readonly class StudentListViewModel
{
    private LengthAwarePaginator $students;

    private Collection $courses;

    private StudentFilterData $filters;

    public function __construct(
        StudentFilterData $filters,
        int $perPage = 20,
        ?array $restrictToCourseIds = null
    ) {
        $this->filters = $filters;

        $coursesQuery = Course::orderBy('name');
        if ($restrictToCourseIds !== null) {
            $coursesQuery->whereIn('id', $restrictToCourseIds);
        }
        $this->courses = $coursesQuery->get();

        $query = Student::query()
            ->with(['courses', 'courseProgress'])
            ->withCount('courses');

        if ($restrictToCourseIds !== null) {
            $query->whereHas('courses', function ($q) use ($restrictToCourseIds) {
                $q->whereIn('courses.id', $restrictToCourseIds);
            });
        }

        if ($filters->search) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'ilike', "%{$filters->search}%")
                    ->orWhere('surname', 'ilike', "%{$filters->search}%")
                    ->orWhere('email', 'ilike', "%{$filters->search}%")
                    ->orWhere('phone', 'ilike', "%{$filters->search}%")
                    ->orWhere('number', 'ilike', "%{$filters->search}%");
            });
        }

        if ($filters->course_id) {
            $query->whereHas('courses', function ($q) use ($filters) {
                $q->where('course_id', $filters->course_id);
            });
        }

        if ($filters->is_new) {
            $query->where('created_at', '>=', now()->subDays(7));
        }

        if ($filters->created_from) {
            $query->whereDate('created_at', '>=', $filters->created_from);
        }

        if ($filters->created_to) {
            $query->whereDate('created_at', '<=', $filters->created_to);
        }

        if ($filters->only_deleted) {
            $query->onlyTrashed();
        }

        $sortBy = in_array($filters->sort_by, ['number', 'created_at']) ? $filters->sort_by : 'created_at';
        $sortDirection = in_array($filters->sort_direction, ['asc', 'desc']) ? $filters->sort_direction : 'desc';

        $this->students = $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }

    public function students(): LengthAwarePaginator
    {
        return $this->students;
    }

    public function courses(): Collection
    {
        return $this->courses;
    }

    public function filters(): StudentFilterData
    {
        return $this->filters;
    }

    public function hasNoStudents(): bool
    {
        return $this->students->isEmpty();
    }

    public function totalCount(): int
    {
        return $this->students->total();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->course_id !== null
            || $this->filters->is_new !== null
            || $this->filters->only_deleted !== null
            || $this->filters->created_from !== null
            || $this->filters->created_to !== null;
    }

    public function currentSort(): string
    {
        return $this->filters->sort_by ?? 'created_at';
    }

    public function currentDirection(): string
    {
        return $this->filters->sort_direction ?? 'desc';
    }

    public function sortUrl(string $column): string
    {
        $currentSort = $this->currentSort();
        $currentDirection = $this->currentDirection();

        $direction = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';

        $params = array_filter([
            'search' => $this->filters->search,
            'course_id' => $this->filters->course_id,
            'is_new' => $this->filters->is_new ? '1' : null,
            'only_deleted' => $this->filters->only_deleted ? '1' : null,
            'created_from' => $this->filters->created_from?->format('d.m.Y'),
            'created_to' => $this->filters->created_to?->format('d.m.Y'),
            'sort_by' => $column,
            'sort_direction' => $direction,
        ]);

        return route('admin.students.index', $params);
    }
}
