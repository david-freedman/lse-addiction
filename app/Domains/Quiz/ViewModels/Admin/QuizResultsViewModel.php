<?php

namespace App\Domains\Quiz\ViewModels\Admin;

use App\Domains\Quiz\Data\QuizResultsFilterData;
use App\Domains\Quiz\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class QuizResultsViewModel
{
    public function __construct(
        private Quiz $quiz,
        private QuizResultsFilterData $filters,
        private int $perPage = 20,
    ) {}

    public function quiz(): Quiz
    {
        return $this->quiz;
    }

    public function filters(): QuizResultsFilterData
    {
        return $this->filters;
    }

    public function attempts(): LengthAwarePaginator
    {
        $query = $this->quiz->attempts()
            ->with('student')
            ->orderByDesc('completed_at');

        if ($this->filters->search) {
            $query->whereHas('student', function ($q) {
                $q->where('first_name', 'ilike', "%{$this->filters->search}%")
                    ->orWhere('last_name', 'ilike', "%{$this->filters->search}%")
                    ->orWhere('email', 'ilike', "%{$this->filters->search}%");
            });
        }

        if ($this->filters->passed !== null) {
            $query->where('passed', $this->filters->passed);
        }

        if ($this->filters->date_from) {
            $query->whereDate('completed_at', '>=', $this->filters->date_from);
        }

        if ($this->filters->date_to) {
            $query->whereDate('completed_at', '<=', $this->filters->date_to);
        }

        return $query->paginate($this->perPage);
    }

    public function statistics(): array
    {
        $allAttempts = $this->quiz->attempts;
        $totalAttempts = $allAttempts->count();

        if ($totalAttempts === 0) {
            return [
                'total_attempts' => 0,
                'pass_rate' => 0,
                'avg_score' => 0,
                'avg_time_seconds' => 0,
                'avg_time_formatted' => '0:00',
            ];
        }

        $passedCount = $allAttempts->where('passed', true)->count();
        $avgScore = $allAttempts->avg('score');
        $maxScore = $allAttempts->first()->max_score ?? 1;
        $avgTimeSeconds = (int) $allAttempts->avg('time_spent_seconds');

        return [
            'total_attempts' => $totalAttempts,
            'pass_rate' => round(($passedCount / $totalAttempts) * 100),
            'avg_score' => round(($avgScore / $maxScore) * 100),
            'avg_time_seconds' => $avgTimeSeconds,
            'avg_time_formatted' => gmdate('i:s', $avgTimeSeconds),
        ];
    }
}
