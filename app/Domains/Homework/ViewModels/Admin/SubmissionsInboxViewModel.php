<?php

namespace App\Domains\Homework\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Data\SubmissionsFilterData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class SubmissionsInboxViewModel
{
    public function __construct(
        public readonly SubmissionsFilterData $filters,
    ) {}

    public function submissions(): LengthAwarePaginator
    {
        $query = HomeworkSubmission::query()
            ->with(['student', 'homework.lesson.module.course']);

        if ($this->filters->isReviewedTab()) {
            $query->whereIn('status', [
                HomeworkSubmissionStatus::Approved,
                HomeworkSubmissionStatus::Rejected,
            ]);
        } elseif ($statusEnum = $this->filters->getStatusEnum()) {
            $query->where('status', $statusEnum);
        }

        if ($this->filters->course_id) {
            $query->whereHas('homework.lesson.module', fn ($q) => $q->where('course_id', $this->filters->course_id));
        }

        if ($this->filters->module_id) {
            $query->whereHas('homework.lesson', fn ($q) => $q->where('module_id', $this->filters->module_id));
        }

        if ($this->filters->lesson_id) {
            $query->whereHas('homework', fn ($q) => $q->where('lesson_id', $this->filters->lesson_id));
        }

        if ($this->filters->search) {
            $searchTerm = $this->filters->search;
            $query->whereHas('student', function ($q) use ($searchTerm) {
                $q->where(function ($sq) use ($searchTerm) {
                    $sq->where('name', 'ilike', "%{$searchTerm}%")
                        ->orWhere('surname', 'ilike', "%{$searchTerm}%")
                        ->orWhere('email', 'ilike', "%{$searchTerm}%");
                });
            });
        }

        return $query
            ->latest('submitted_at')
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * @return array{all: int, pending: int, revision_requested: int, reviewed: int}
     */
    public function statusCounts(): array
    {
        return [
            'all' => HomeworkSubmission::count(),
            'pending' => HomeworkSubmission::where('status', HomeworkSubmissionStatus::Pending)->count(),
            'revision_requested' => HomeworkSubmission::where('status', HomeworkSubmissionStatus::RevisionRequested)->count(),
            'reviewed' => HomeworkSubmission::whereIn('status', [
                HomeworkSubmissionStatus::Approved,
                HomeworkSubmissionStatus::Rejected,
            ])->count(),
        ];
    }

    /**
     * @return Collection<int, Course>
     */
    public function courses(): Collection
    {
        return Course::query()
            ->whereHas('modules.lessons.homework.submissions')
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
            ->whereHas('lessons.homework.submissions')
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
            ->whereHas('homework.submissions')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);
    }

    public function isFiltered(): bool
    {
        return $this->filters->course_id !== null
            || $this->filters->module_id !== null
            || $this->filters->lesson_id !== null
            || $this->filters->search !== null;
    }

    public function hasNoSubmissions(): bool
    {
        return HomeworkSubmission::count() === 0;
    }

    public function currentStatus(): ?string
    {
        return $this->filters->status;
    }
}
