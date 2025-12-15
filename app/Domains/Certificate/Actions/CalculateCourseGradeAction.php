<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Student\Models\Student;

final class CalculateCourseGradeAction
{
    public function __invoke(Student $student, Course $course): float
    {
        $finalQuiz = $course->getFinalQuiz();

        if (!$finalQuiz) {
            return 100.0;
        }

        $bestAttempt = StudentQuizAttempt::where('student_id', $student->id)
            ->where('quiz_id', $finalQuiz->id)
            ->where('passed', true)
            ->orderByDesc('score')
            ->first();

        if (!$bestAttempt) {
            return 0.0;
        }

        return $bestAttempt->max_score > 0
            ? round(($bestAttempt->score / $bestAttempt->max_score) * 100, 2)
            : 100.0;
    }
}
