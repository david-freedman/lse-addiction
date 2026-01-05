<?php

namespace App\Domains\Homework\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Data\HomeworkListFilterData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\Homework;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class HomeworkListViewModel
{
    public function __construct(
        public readonly HomeworkListFilterData $filters,
    ) {}

    public function homeworks(): LengthAwarePaginator
    {
        $query = Homework::query()
            ->with(['lesson.module.course'])
            ->withCount(['submissions'])
            ->withCount(['submissions as pending_count' => fn ($q) => $q->where('status', HomeworkSubmissionStatus::Pending)])
            ->withCount(['submissions as needs_attention_count' => fn ($q) => $q->whereIn('status', [
                HomeworkSubmissionStatus::Pending,
                HomeworkSubmissionStatus::RevisionRequested,
            ])])
            ->withCount(['submissions as reviewed_count' => fn ($q) => $q->whereIn('status', [
                HomeworkSubmissionStatus::Approved,
                HomeworkSubmissionStatus::Rejected,
            ])]);

        if ($this->filters->course_id) {
            $query->whereHas('lesson.module', fn ($q) => $q->where('course_id', $this->filters->course_id));
        }

        if ($this->filters->module_id) {
            $query->whereHas('lesson', fn ($q) => $q->where('module_id', $this->filters->module_id));
        }

        if ($this->filters->lesson_id) {
            $query->where('lesson_id', $this->filters->lesson_id);
        }

        if ($this->filters->has_pending) {
            $query->whereHas('submissions', fn ($q) => $q->whereIn('status', [
                HomeworkSubmissionStatus::Pending,
                HomeworkSubmissionStatus::RevisionRequested,
            ]));
        }

        if ($this->filters->search) {
            $query->whereHas('lesson', fn ($q) => $q->where('name', 'ilike', "%{$this->filters->search}%"));
        }

        return $query
            ->orderBy(
                Lesson::query()
                    ->select('lessons.order')
                    ->join('modules', 'modules.id', '=', 'lessons.module_id')
                    ->join('courses', 'courses.id', '=', 'modules.course_id')
                    ->whereColumn('lessons.id', 'homeworks.lesson_id')
                    ->orderBy('courses.name')
                    ->orderBy('modules.order')
                    ->orderBy('lessons.order')
                    ->limit(1)
            )
            ->orderBy(
                Course::query()
                    ->select('courses.name')
                    ->join('modules', 'modules.course_id', '=', 'courses.id')
                    ->join('lessons', 'lessons.module_id', '=', 'modules.id')
                    ->whereColumn('lessons.id', 'homeworks.lesson_id')
                    ->limit(1)
            )
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * @return Collection<int, Course>
     */
    public function courses(): Collection
    {
        return Course::query()
            ->whereHas('modules.lessons.homework')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @return Collection<int, Module>
     */
    public function modules(): Collection
    {
        if (!$this->filters->course_id) {
            return collect();
        }

        return Module::query()
            ->where('course_id', $this->filters->course_id)
            ->whereHas('lessons.homework')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function lessons(): Collection
    {
        if (!$this->filters->module_id) {
            return collect();
        }

        return Lesson::query()
            ->where('module_id', $this->filters->module_id)
            ->whereHas('homework')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);
    }

    /**
     * @return array{total_homeworks: int, total_needs_attention: int, total_pending: int, total_approved: int}
     */
    public function globalStats(): array
    {
        $pendingStatuses = [
            HomeworkSubmissionStatus::Pending->value,
            HomeworkSubmissionStatus::RevisionRequested->value,
        ];

        return [
            'total_homeworks' => Homework::count(),
            'total_needs_attention' => Homework::query()
                ->whereHas('submissions', fn ($q) => $q->whereIn('status', $pendingStatuses))
                ->count(),
            'total_pending' => Homework::query()
                ->whereHas('submissions', fn ($q) => $q->where('status', HomeworkSubmissionStatus::Pending))
                ->count(),
            'total_approved' => Homework::query()
                ->whereHas('submissions', fn ($q) => $q->where('status', HomeworkSubmissionStatus::Approved))
                ->count(),
        ];
    }

    public function isFiltered(): bool
    {
        return $this->filters->course_id !== null
            || $this->filters->module_id !== null
            || $this->filters->lesson_id !== null
            || $this->filters->has_pending !== null
            || $this->filters->search !== null;
    }

    public function hasNoHomeworks(): bool
    {
        return Homework::count() === 0;
    }
}
