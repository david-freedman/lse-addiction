<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Data\CourseFilterData;
use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Teacher\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class AdminCourseListViewModel
{
    public function __construct(
        private CourseFilterData $filters,
        private int $perPage = 20,
        private ?User $user = null,
    ) {}

    public function courses(): LengthAwarePaginator
    {
        $query = Course::query()
            ->with(['teacher', 'author'])
            ->withCount(['modules', 'students']);

        if ($this->user && ! $this->user->isAdmin()) {
            $query->where(function ($q) {
                $q->where('teacher_id', $this->user->teacherProfile?->id)
                    ->orWhere('author_id', $this->user->id);
            });
        }

        if ($this->filters->search) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', "%{$this->filters->search}%")
                    ->orWhere('number', 'ilike', "%{$this->filters->search}%");
            });
        }

        if ($this->filters->status) {
            $query->where('status', $this->filters->status);
        }

        if ($this->filters->teacher_id) {
            $query->where('teacher_id', $this->filters->teacher_id);
        }

        if ($this->filters->created_from) {
            $query->whereDate('created_at', '>=', $this->filters->created_from);
        }

        if ($this->filters->created_to) {
            $query->whereDate('created_at', '<=', $this->filters->created_to);
        }

        return $query->orderByDesc('created_at')->paginate($this->perPage);
    }

    public function filters(): CourseFilterData
    {
        return $this->filters;
    }

    /**
     * @return CourseStatus[]
     */
    public function statuses(): array
    {
        return CourseStatus::cases();
    }

    public function teachers(): Collection
    {
        return Teacher::orderBy('last_name')->orderBy('first_name')->get();
    }

    public function hasNoCourses(): bool
    {
        return $this->courses()->isEmpty();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->status !== null
            || $this->filters->teacher_id !== null
            || $this->filters->created_from !== null
            || $this->filters->created_to !== null;
    }
}
