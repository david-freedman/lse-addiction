<?php

namespace App\Domains\Lesson\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Data\CommentFilterData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Lesson\Models\LessonComment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class CommentListViewModel
{
    public function __construct(
        public readonly CommentFilterData $filters,
        public readonly ?User $user = null,
    ) {}

    public function comments(): LengthAwarePaginator
    {
        $query = LessonComment::query()
            ->with(['student', 'user', 'lesson.module.course', 'replies' => function ($q) {
                $q->with(['student', 'user'])->orderBy('created_at');
            }])
            ->whereNull('parent_id');

        if ($this->user?->isTeacher()) {
            $query->whereHas('lesson.module.course', function ($q) {
                $q->where('teacher_id', $this->user->teacher?->id);
            });
        }

        if ($this->filters->course_id) {
            $query->whereHas('lesson.module', fn ($q) => $q->where('course_id', $this->filters->course_id));
        }

        if ($this->filters->lesson_id) {
            $query->where('lesson_id', $this->filters->lesson_id);
        }

        if ($this->filters->search) {
            $searchTerm = $this->filters->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('content', 'ilike', "%{$searchTerm}%")
                    ->orWhereHas('student', function ($sq) use ($searchTerm) {
                        $sq->where('name', 'ilike', "%{$searchTerm}%")
                            ->orWhere('surname', 'ilike', "%{$searchTerm}%");
                    });
            });
        }

        return $query
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function courses(): Collection
    {
        $query = Course::query()
            ->whereHas('modules.lessons.comments');

        if ($this->user?->isTeacher()) {
            $query->where('teacher_id', $this->user->teacher?->id);
        }

        return $query->orderBy('name')->get(['id', 'name']);
    }

    public function lessons(): Collection
    {
        if (!$this->filters->course_id) {
            return collect();
        }

        return Lesson::query()
            ->whereHas('module', fn ($q) => $q->where('course_id', $this->filters->course_id))
            ->whereHas('comments')
            ->with('module:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'module_id']);
    }

    public function isFiltered(): bool
    {
        return $this->filters->isFiltered();
    }

    public function totalCount(): int
    {
        $query = LessonComment::query()->whereNull('parent_id');

        if ($this->user?->isTeacher()) {
            $query->whereHas('lesson.module.course', function ($q) {
                $q->where('teacher_id', $this->user->teacher?->id);
            });
        }

        return $query->count();
    }

    public function hasNoComments(): bool
    {
        return $this->totalCount() === 0;
    }
}
