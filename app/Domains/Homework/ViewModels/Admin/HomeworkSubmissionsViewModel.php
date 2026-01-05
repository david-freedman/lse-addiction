<?php

namespace App\Domains\Homework\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\Homework;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class HomeworkSubmissionsViewModel
{
    public function __construct(
        public readonly Homework $homework,
        public readonly ?string $status = null,
        public readonly ?string $search = null,
    ) {}

    public function course(): Course
    {
        return $this->homework->lesson->module->course;
    }

    public function module(): Module
    {
        return $this->homework->lesson->module;
    }

    public function lesson(): Lesson
    {
        return $this->homework->lesson;
    }

    public function submissions(): LengthAwarePaginator
    {
        $query = $this->homework->submissions()
            ->with(['student'])
            ->latest('submitted_at');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->whereHas('student', function ($q) {
                $q->where('first_name', 'ilike', "%{$this->search}%")
                    ->orWhere('last_name', 'ilike', "%{$this->search}%")
                    ->orWhere('email', 'ilike', "%{$this->search}%");
            });
        }

        return $query->paginate(20);
    }

    public function statusCounts(): array
    {
        $counts = $this->homework->submissions()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'all' => array_sum($counts),
            'pending' => $counts[HomeworkSubmissionStatus::Pending->value] ?? 0,
            'revision_requested' => $counts[HomeworkSubmissionStatus::RevisionRequested->value] ?? 0,
            'approved' => $counts[HomeworkSubmissionStatus::Approved->value] ?? 0,
            'rejected' => $counts[HomeworkSubmissionStatus::Rejected->value] ?? 0,
        ];
    }

    public function statuses(): array
    {
        return HomeworkSubmissionStatus::cases();
    }
}
