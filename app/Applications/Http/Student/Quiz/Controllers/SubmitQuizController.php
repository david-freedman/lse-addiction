<?php

namespace App\Applications\Http\Student\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Actions\MarkLessonCompletedAction;
use App\Domains\Quiz\Actions\CheckQuizAnswersAction;
use App\Domains\Quiz\Actions\SaveQuizAttemptAction;
use App\Domains\Quiz\ViewModels\QuizDetailViewModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SubmitQuizController
{
    public function __construct(
        private CheckQuizAnswersAction $checkQuizAnswersAction,
        private SaveQuizAttemptAction $saveQuizAttemptAction,
        private MarkLessonCompletedAction $markLessonCompletedAction
    ) {}

    public function __invoke(Request $request, Course $course, Lesson $lesson): View
    {
        $student = $request->user();

        if (! $student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        if ($lesson->type !== LessonType::Quiz) {
            return redirect()->route('student.lessons.show', [$course, $lesson]);
        }

        $quiz = $lesson->quiz;

        if (! $quiz) {
            throw new NotFoundHttpException('Квіз не знайдено');
        }

        $viewModel = new QuizDetailViewModel($quiz, $lesson, $course, $student);

        if (! $viewModel->canAttempt()) {
            return redirect()
                ->route('student.quiz.show', [$course, $lesson])
                ->with('error', 'Ви вичерпали всі спроби');
        }

        $answers = $request->input('answers', []);
        $startedAt = session('quiz_started_at', now());

        if ($startedAt instanceof string) {
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

        if ($result->passed) {
            ($this->markLessonCompletedAction)($student, $lesson, $course);
        }

        session()->forget('quiz_started_at');

        return view('student.quiz.result', [
            'viewModel' => $viewModel,
            'course' => $course,
            'lesson' => $lesson,
            'quiz' => $quiz,
            'result' => $result,
            'attempt' => $attempt,
        ]);
    }
}
