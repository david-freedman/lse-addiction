<?php

namespace App\Applications\Http\Student\Webinar\Controllers;

use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShowWebinarQuizController
{
    public function __invoke(Request $request, Webinar $webinar): View
    {
        $student = $request->user();

        if (!$webinar->isRegistered($student)) {
            abort(403, 'Ви не зареєстровані на цей вебінар');
        }

        if (!in_array($webinar->status->value, ['ended', 'recorded'])) {
            abort(403, 'Тестування доступне лише після завершення вебінару');
        }

        $quiz = $webinar->quiz;

        if (!$quiz) {
            throw new NotFoundHttpException('Квіз не знайдено');
        }

        $quiz->load(['questions.answers']);

        $attemptsUsed = $student->quizAttempts()->where('quiz_id', $quiz->id)->count();
        $maxAttempts = $quiz->max_attempts;
        $canAttempt = !$maxAttempts || $attemptsUsed < $maxAttempts;
        $hasPassed = $student->quizAttempts()->where('quiz_id', $quiz->id)->where('passed', true)->exists();

        session(['webinar_quiz_started_at' => now()]);

        return view('student.webinar.quiz', compact('webinar', 'quiz', 'canAttempt', 'hasPassed', 'attemptsUsed', 'maxAttempts'));
    }
}
