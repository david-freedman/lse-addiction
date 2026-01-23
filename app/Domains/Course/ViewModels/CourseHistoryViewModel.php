<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Models\ActivityLog;
use App\Domains\Course\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class CourseHistoryViewModel
{
    public function __construct(
        private Course $course,
        private ?string $subjectType = null,
        private ?int $performedBy = null,
        private ?string $dateFrom = null,
        private ?string $dateTo = null,
    ) {}

    public function logs(): LengthAwarePaginator
    {
        $query = ActivityLog::query()
            ->where('course_id', $this->course->id)
            ->with('performer');

        if ($this->subjectType) {
            $query->where('subject_type', $this->subjectType);
        }

        if ($this->performedBy) {
            $query->where('performed_by', $this->performedBy);
        }

        if ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom.' 00:00:00');
        }

        if ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo.' 23:59:59');
        }

        return $query->orderByDesc('created_at')->paginate(20)->withQueryString();
    }

    public function admins(): Collection
    {
        return User::query()
            ->whereIn('id', function ($query) {
                $query->select('performed_by')
                    ->from('activity_logs')
                    ->where('course_id', $this->course->id)
                    ->whereNotNull('performed_by')
                    ->distinct();
            })
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function subjectTypes(): array
    {
        return [
            ActivitySubject::Course->value => 'Курс',
            ActivitySubject::Module->value => 'Модуль',
            ActivitySubject::Lesson->value => 'Урок',
            ActivitySubject::Quiz->value => 'Квіз',
        ];
    }

    public function filters(): array
    {
        return [
            'subject_type' => $this->subjectType,
            'performed_by' => $this->performedBy,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        ];
    }

    public function isFiltered(): bool
    {
        return $this->subjectType !== null
            || $this->performedBy !== null
            || $this->dateFrom !== null
            || $this->dateTo !== null;
    }
}
