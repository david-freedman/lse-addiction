<?php

namespace App\Domains\Quiz\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\Data\QuizResultsIndexFilterData;
use App\Domains\Quiz\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class QuizResultsIndexViewModel
{
    public function __construct(
        public readonly QuizResultsIndexFilterData $filters,
        private readonly ?array $restrictToCourseIds = null,
    ) {}

    public function attempts(): LengthAwarePaginator
    {
        $query = StudentQuizAttempt::query()
            ->with(['student', 'quiz.quizzable.module.course']);

        $isSurvey = $this->filters->isSurveysTab();
        $query->whereHas('quiz', fn ($q) => $q->where('is_survey', $isSurvey));

        $query->whereHas('quiz', fn ($q) => $q->where('quizzable_type', Lesson::class));

        if ($this->restrictToCourseIds !== null) {
            $query->whereHas('quiz.quizzable.module', fn ($q) => $q->whereIn('course_id', $this->restrictToCourseIds));
        }

        if ($this->filters->isQuizzesTab() && ($passed = $this->filters->getPassedFilter()) !== null) {
            $query->where('passed', $passed);
        }

        if ($this->filters->course_id) {
            $query->whereHas('quiz.quizzable.module', fn ($q) => $q->where('course_id', $this->filters->course_id));
        }

        if ($this->filters->module_id) {
            $query->whereHas('quiz.quizzable', fn ($q) => $q->where('module_id', $this->filters->module_id));
        }

        if ($this->filters->lesson_id) {
            $query->whereHas('quiz', fn ($q) => $q->where('quizzable_id', $this->filters->lesson_id)->where('quizzable_type', Lesson::class));
        }

        if ($this->filters->quiz_id) {
            $query->where('quiz_id', $this->filters->quiz_id);
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
            ->latest('completed_at')
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * @return array{total: int, pass_rate: int, avg_score: int}
     */
    public function statistics(): array
    {
        if ($this->filters->isSurveysTab()) {
            return ['total' => 0, 'pass_rate' => 0, 'avg_score' => 0];
        }

        $query = $this->baseQuery();

        $total = (clone $query)->count();

        if ($total === 0) {
            return ['total' => 0, 'pass_rate' => 0, 'avg_score' => 0];
        }

        $passed = (clone $query)->where('passed', true)->count();

        $avgQuery = clone $query;
        $avgScore = (int) round(
            $avgQuery->selectRaw('AVG(CASE WHEN max_score > 0 THEN (score / max_score) * 100 ELSE 0 END) as avg')->value('avg') ?? 0
        );

        return [
            'total' => $total,
            'pass_rate' => (int) round(($passed / $total) * 100),
            'avg_score' => $avgScore,
        ];
    }

    /**
     * @return array{all: int, passed: int, failed: int}
     */
    public function statusCounts(): array
    {
        if ($this->filters->isSurveysTab()) {
            return ['all' => 0, 'passed' => 0, 'failed' => 0];
        }

        $query = $this->baseQuery();

        return [
            'all' => (clone $query)->count(),
            'passed' => (clone $query)->where('passed', true)->count(),
            'failed' => (clone $query)->where('passed', false)->count(),
        ];
    }

    /**
     * @return Collection<int, Course>
     */
    public function courses(): Collection
    {
        $isSurvey = $this->filters->isSurveysTab();

        $query = Course::query()
            ->whereHas('modules.lessons.quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('modules.lessons.quiz.attempts')
            ->orderBy('name');

        if ($this->restrictToCourseIds !== null) {
            $query->whereIn('id', $this->restrictToCourseIds);
        }

        return $query->get(['id', 'name']);
    }

    /**
     * @return Collection<int, Module>
     */
    public function modules(): Collection
    {
        if (!$this->filters->course_id) {
            return collect();
        }

        $isSurvey = $this->filters->isSurveysTab();

        return Module::query()
            ->where('course_id', $this->filters->course_id)
            ->whereHas('lessons.quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('lessons.quiz.attempts')
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

        $isSurvey = $this->filters->isSurveysTab();

        return Lesson::query()
            ->where('module_id', $this->filters->module_id)
            ->whereHas('quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('quiz.attempts')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function quizzes(): Collection
    {
        if (!$this->filters->lesson_id) {
            return collect();
        }

        $isSurvey = $this->filters->isSurveysTab();

        return Quiz::query()
            ->where('quizzable_type', Lesson::class)
            ->where('quizzable_id', $this->filters->lesson_id)
            ->where('is_survey', $isSurvey)
            ->whereHas('attempts')
            ->get(['id', 'title']);
    }

    public function hasNoAttempts(): bool
    {
        $query = $this->baseQuery();

        return $query->count() === 0;
    }

    public function currentTab(): string
    {
        return $this->filters->isQuizzesTab() ? 'quizzes' : 'surveys';
    }

    public function currentStatus(): ?string
    {
        return $this->filters->status;
    }

    private function baseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $isSurvey = $this->filters->isSurveysTab();

        $query = StudentQuizAttempt::query()
            ->whereHas('quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('quiz', fn ($q) => $q->where('quizzable_type', Lesson::class));

        if ($this->restrictToCourseIds !== null) {
            $query->whereHas('quiz.quizzable.module', fn ($q) => $q->whereIn('course_id', $this->restrictToCourseIds));
        }

        if ($this->filters->course_id) {
            $query->whereHas('quiz.quizzable.module', fn ($q) => $q->where('course_id', $this->filters->course_id));
        }

        if ($this->filters->module_id) {
            $query->whereHas('quiz.quizzable', fn ($q) => $q->where('module_id', $this->filters->module_id));
        }

        if ($this->filters->lesson_id) {
            $query->whereHas('quiz', fn ($q) => $q->where('quizzable_id', $this->filters->lesson_id)->where('quizzable_type', Lesson::class));
        }

        if ($this->filters->quiz_id) {
            $query->where('quiz_id', $this->filters->quiz_id);
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

        return $query;
    }
}
