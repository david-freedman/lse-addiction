<?php

namespace App\Domains\Quiz\ViewModels\Admin;

use App\Domains\Progress\Models\StudentQuizAttempt;
use Illuminate\Support\Collection;

readonly class QuizAttemptDetailViewModel
{
    public function __construct(
        private StudentQuizAttempt $attempt,
    ) {}

    public function attempt(): StudentQuizAttempt
    {
        return $this->attempt;
    }

    public function questionsWithAnswers(): Collection
    {
        $quiz = $this->attempt->quiz;
        $studentAnswers = $this->attempt->answers ?? [];
        $questions = $quiz->questions()->with('answers')->orderBy('order')->get();

        return $questions->map(function ($question) use ($studentAnswers) {
            $questionId = (string) $question->id;
            $studentAnswer = $studentAnswers[$questionId] ?? null;
            $correctAnswers = $question->answers->where('is_correct', true);

            $isCorrect = $this->checkIfCorrect($question, $studentAnswer, $correctAnswers);
            $studentAnswerText = $this->formatStudentAnswer($question, $studentAnswer);
            $correctAnswerText = $this->formatCorrectAnswer($question, $correctAnswers);

            return [
                'question' => $question,
                'student_answer' => $studentAnswerText,
                'correct_answer' => $correctAnswerText,
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $question->points : 0,
                'max_points' => $question->points,
            ];
        });
    }

    private function checkIfCorrect($question, $studentAnswer, $correctAnswers): bool
    {
        if ($studentAnswer === null) {
            return false;
        }

        $correctIds = $correctAnswers->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        sort($correctIds);

        $studentIds = is_array($studentAnswer) ? $studentAnswer : [(string) $studentAnswer];
        $studentIds = array_map('strval', $studentIds);
        sort($studentIds);

        return $correctIds === $studentIds;
    }

    private function formatStudentAnswer($question, $studentAnswer): string
    {
        if ($studentAnswer === null) {
            return 'Не відповів';
        }

        $answerIds = is_array($studentAnswer) ? $studentAnswer : [$studentAnswer];
        $answers = $question->answers->whereIn('id', $answerIds);

        return $answers->pluck('answer_text')->filter()->implode(', ') ?: 'Не відповів';
    }

    private function formatCorrectAnswer($question, $correctAnswers): string
    {
        return $correctAnswers->pluck('answer_text')->filter()->implode(', ') ?: '-';
    }
}
