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

    public function __construct(StudentFilterData $filters, int $perPage = 20)
    {
        $this->filters = $filters;

        $this->courses = Course::orderBy('name')->get();

        $query = Student::query()
            ->with(['courses' => function ($query) {
                $query->withPivot(['enrolled_at', 'status', 'lessons_completed', 'total_lessons']);
            }])
            ->withCount('courses');

        if ($filters->search) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'ilike', "%{$filters->search}%")
                    ->orWhere('surname', 'ilike', "%{$filters->search}%")
                    ->orWhere('email', 'ilike', "%{$filters->search}%")
                    ->orWhere('phone', 'ilike', "%{$filters->search}%");
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

        $this->students = $query->orderBy('created_at', 'desc')->paginate($perPage);
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
}
