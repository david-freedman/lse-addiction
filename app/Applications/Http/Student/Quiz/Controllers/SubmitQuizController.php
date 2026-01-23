<?php

namespace App\Applications\Http\Student\Quiz\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Actions\MarkLessonCompletedAction;
use App\Domains\Quiz\Actions\CheckQuizAnswersAction;
use App\Domains\Quiz\Actions\DeleteQuizDraftAction;
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
        private MarkLessonCompletedAction $markLessonCompletedAction,
        private DeleteQuizDraftAction $deleteQuizDraftAction
    ) {}

    public function __invoke(Request $request, Course $course, Lesson $lesson): View
    {
        $student = $request->user();

        if (!$student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        if (!in_array($lesson->type, [LessonType::Quiz, LessonType::Survey])) {
            return redirect()->route('student.lessons.show', [$course, $lesson]);
        }

        $quiz = $lesson->quiz;

        if (!$quiz) {
            throw new NotFoundHttpException('Квіз не знайдено');
        }

        $viewModel = new QuizDetailViewModel($quiz, $lesson, $course, $student);

        if (!$viewModel->canAttempt()) {
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

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Quiz,
            'subject_id' => $quiz->id,
            'activity_type' => ActivityType::QuizSubmitted,
            'description' => 'Quiz submitted',
            'properties' => [
                'quiz_id' => $quiz->id,
                'lesson_id' => $lesson->id,
                'lesson_title' => $lesson->title,
                'course_id' => $course->id,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
                'attempt_id' => $attempt->id,
                'score_percentage' => $result->score_percentage,
                'passed' => $result->passed,
                'lesson_type' => $lesson->type->value,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        if ($result->passed) {
            ($this->markLessonCompletedAction)($student, $lesson, $course);
        }

        ($this->deleteQuizDraftAction)($student, $lesson);
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
