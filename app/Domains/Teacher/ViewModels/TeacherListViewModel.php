<?php

namespace App\Domains\Teacher\ViewModels;

use App\Domains\Teacher\Data\TeacherFilterData;
use App\Domains\Teacher\Models\Teacher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class TeacherListViewModel
{
    private LengthAwarePaginator $teachers;

    private TeacherFilterData $filters;

    private Collection $specializations;

    private Collection $workplaces;

    public function __construct(TeacherFilterData $filters, int $perPage = 20)
    {
        $this->filters = $filters;

        $this->specializations = Teacher::query()
            ->whereNotNull('specialization')
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization');

        $this->workplaces = Teacher::query()
            ->whereNotNull('workplace')
            ->where('workplace', '!=', '')
            ->distinct()
            ->orderBy('workplace')
            ->pluck('workplace');

        $query = Teacher::query()
            ->with('user')
            ->search($filters->search);

        if ($filters->specialization) {
            $query->where('specialization', $filters->specialization);
        }

        if ($filters->workplace) {
            $query->where('workplace', $filters->workplace);
        }

        $this->teachers = $query->orderBy('last_name')->orderBy('first_name')->paginate($perPage);
    }

    public function teachers(): LengthAwarePaginator
    {
        return $this->teachers;
    }

    public function filters(): TeacherFilterData
    {
        return $this->filters;
    }

    public function hasNoTeachers(): bool
    {
        return $this->teachers->isEmpty();
    }

    public function totalCount(): int
    {
        return $this->teachers->total();
    }

    public function specializations(): Collection
    {
        return $this->specializations;
    }

    public function workplaces(): Collection
    {
        return $this->workplaces;
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->specialization !== null
            || $this->filters->workplace !== null;
    }
}
