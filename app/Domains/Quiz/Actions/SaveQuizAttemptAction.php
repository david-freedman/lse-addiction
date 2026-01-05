<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\Data\CheckQuizResult;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Student\Models\Student;
use Carbon\Carbon;

final class SaveQuizAttemptAction
{
    public function __invoke(
        Student $student,
        Quiz $quiz,
        CheckQuizResult $result,
        array $answers,
        Carbon $startedAt
    ): StudentQuizAttempt {
        $attemptNumber = $student->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->count() + 1;

        $timeSpent = (int) max(0, $startedAt->diffInSeconds(now()));

        return StudentQuizAttempt::create([
            'student_id' => $student->id,
            'quiz_id' => $quiz->id,
            'attempt_number' => $attemptNumber,
            'score' => $result->score,
            'max_score' => $result->maxScore,
            'passed' => $result->passed,
            'answers' => $answers,
            'time_spent_seconds' => $timeSpent,
            'started_at' => $startedAt,
            'completed_at' => now(),
        ]);
    }
}
