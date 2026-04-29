<?php

namespace App\Applications\Http\Student\Webinar\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Quiz\Actions\CheckQuizAnswersAction;
use App\Domains\Quiz\Actions\SaveQuizAttemptAction;
use App\Domains\Webinar\Models\Webinar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SubmitWebinarQuizController
{
    public function __construct(
        private readonly CheckQuizAnswersAction $checkQuizAnswersAction,
        private readonly SaveQuizAttemptAction $saveQuizAttemptAction
    ) {}

    public function __invoke(Request $request, Webinar $webinar): View|\Illuminate\Http\RedirectResponse
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

        $attemptsUsed = $student->quizAttempts()->where('quiz_id', $quiz->id)->count();
        if ($quiz->max_attempts && $attemptsUsed >= $quiz->max_attempts) {
            return redirect()
                ->route('student.webinar.quiz.show', $webinar)
                ->with('error', 'Ви вичерпали всі спроби');
        }

        $answers = $request->input('answers', []);
        $startedAt = session('webinar_quiz_started_at', now());

        if (is_string($startedAt)) {
            $startedAt = Carbon::parse($startedAt);
        }

        $result = ($this->checkQuizAnswersAction)($quiz, $answers);

        $attempt = ($this->saveQuizAttemptAction)(
            $student,
            $quiz,
            $result,
            $answers,
            $startedAt
        );

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Quiz,
            'subject_id' => $quiz->id,
            'activity_type' => ActivityType::QuizSubmitted,
            'description' => 'Webinar Quiz submitted',
            'properties' => [
                'quiz_id' => $quiz->id,
                'webinar_id' => $webinar->id,
                'webinar_title' => $webinar->title,
                'student_id' => $student->id,
                'student_email' => $student->email->value ?? $student->email,
                'attempt_id' => $attempt->id,
                'score_percentage' => $result->scorePercentage(),
                'passed' => $result->passed,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        session()->forget('webinar_quiz_started_at');

        return view('student.webinar.quiz-result', compact('webinar', 'quiz', 'result', 'attempt'));
    }
}
