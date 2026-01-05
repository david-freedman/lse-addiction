<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Data\CheckQuizResult;
use App\Domains\Quiz\Enums\QuestionType;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Quiz\Models\QuizQuestion;

final class CheckQuizAnswersAction
{
    public function __invoke(Quiz $quiz, array $answers): CheckQuizResult
    {
        $quiz->load('questions.answers');

        if ($quiz->isSurvey()) {
            return $this->handleSurvey($quiz, $answers);
        }

        $totalScore = 0;
        $maxScore = 0;
        $questionResults = [];

        foreach ($quiz->questions as $question) {
            $maxScore += $question->points;
            $questionAnswer = $answers[$question->id] ?? null;

            $result = $this->checkQuestion($question, $questionAnswer);
            $questionResults[$question->id] = $result;

            if ($result['correct']) {
                $totalScore += $question->points;
            } elseif (isset($result['partial_score'])) {
                $totalScore += (int) round($question->points * $result['partial_score']);
            }
        }

        $passed = $maxScore > 0
            ? ($totalScore / $maxScore * 100) >= $quiz->passing_score
            : true;

        return new CheckQuizResult(
            score: $totalScore,
            maxScore: $maxScore,
            passed: $passed,
            questionResults: $questionResults
        );
    }

    private function handleSurvey(Quiz $quiz, array $answers): CheckQuizResult
    {
        $questionResults = [];

        foreach ($quiz->questions as $question) {
            $questionAnswer = $answers[$question->id] ?? null;
            $questionResults[$question->id] = [
                'correct' => true,
                'selected' => $questionAnswer['selected'] ?? $questionAnswer['categories'] ?? [],
                'correctAnswers' => [],
            ];
        }

        return new CheckQuizResult(
            score: 0,
            maxScore: 0,
            passed: true,
            questionResults: $questionResults
        );
    }

    private function checkQuestion(QuizQuestion $question, ?array $answer): array
    {
        if (! $answer) {
            return [
                'correct' => false,
                'selected' => [],
                'correctAnswers' => $question->correctAnswers()->pluck('id')->toArray(),
            ];
        }

        return match ($question->type) {
            QuestionType::SingleChoice => $this->checkSingleChoice($question, $answer),
            QuestionType::MultipleChoice => $this->checkMultipleChoice($question, $answer),
            QuestionType::ImageSelect => $this->checkImageSelect($question, $answer),
            QuestionType::DragDrop => $this->checkDragDrop($question, $answer),
            QuestionType::Ordering => $this->checkOrdering($question, $answer),
        };
    }

    private function checkSingleChoice(QuizQuestion $question, array $answer): array
    {
        $selected = $answer['selected'] ?? [];
        $correctAnswers = $question->correctAnswers()->pluck('id')->toArray();

        $isCorrect = count($selected) === 1
            && count($correctAnswers) === 1
            && (int) $selected[0] === $correctAnswers[0];

        return [
            'correct' => $isCorrect,
            'selected' => $selected,
            'correctAnswers' => $correctAnswers,
        ];
    }

    private function checkMultipleChoice(QuizQuestion $question, array $answer): array
    {
        $selected = array_map('intval', $answer['selected'] ?? []);
        sort($selected);

        $correctAnswers = $question->correctAnswers()->pluck('id')->toArray();
        sort($correctAnswers);

        $isCorrect = $selected === $correctAnswers;

        return [
            'correct' => $isCorrect,
            'selected' => $selected,
            'correctAnswers' => $correctAnswers,
        ];
    }

    private function checkImageSelect(QuizQuestion $question, array $answer): array
    {
        $selected = array_map('intval', $answer['selected'] ?? []);
        sort($selected);

        $correctAnswers = $question->correctAnswers()->pluck('id')->toArray();
        sort($correctAnswers);

        $isCorrect = $selected === $correctAnswers;

        return [
            'correct' => $isCorrect,
            'selected' => $selected,
            'correctAnswers' => $correctAnswers,
        ];
    }

    private function checkDragDrop(QuizQuestion $question, array $answer): array
    {
        $categories = $answer['categories'] ?? [];
        $allCorrect = true;

        $answersByCategory = $question->answers()
            ->whereNotNull('category')
            ->get()
            ->groupBy('category');

        $correctMapping = [];
        foreach ($answersByCategory as $category => $categoryAnswers) {
            $correctMapping[$category] = $categoryAnswers->pluck('id')->toArray();
        }

        foreach ($correctMapping as $category => $correctIds) {
            $userIds = array_map('intval', $categories[$category] ?? []);
            sort($userIds);
            sort($correctIds);

            if ($userIds !== $correctIds) {
                $allCorrect = false;
                break;
            }
        }

        return [
            'correct' => $allCorrect,
            'selected' => $categories,
            'correctAnswers' => $correctMapping,
        ];
    }

    private function checkOrdering(QuizQuestion $question, array $answer): array
    {
        $userOrder = array_map('intval', $answer['order'] ?? []);
        $correctAnswers = $question->answers()
            ->orderBy('correct_order')
            ->pluck('id')
            ->toArray();

        $totalItems = count($correctAnswers);
        $correctPositions = 0;

        foreach ($userOrder as $position => $answerId) {
            if (isset($correctAnswers[$position]) && $answerId === $correctAnswers[$position]) {
                $correctPositions++;
            }
        }

        $isFullyCorrect = $correctPositions === $totalItems && $totalItems > 0;
        $partialScore = $totalItems > 0 ? $correctPositions / $totalItems : 0;

        return [
            'correct' => $isFullyCorrect,
            'partial_score' => $isFullyCorrect ? null : $partialScore,
            'selected' => $userOrder,
            'correctAnswers' => $correctAnswers,
        ];
    }
}
