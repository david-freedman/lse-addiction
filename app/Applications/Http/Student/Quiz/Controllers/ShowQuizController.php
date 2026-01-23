<?php

namespace App\Applications\Http\Student\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Actions\LoadQuizDraftAction;
use App\Domains\Quiz\ViewModels\QuizDetailViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShowQuizController
{
    public function __construct(
        private readonly LoadQuizDraftAction $loadQuizDraftAction
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

        if (!$lesson->module->isUnlocked($student)) {
            abort(403, 'Цей модуль ще заблоковано');
        }

        if ($lesson->isFinalTest() && !$lesson->module->allRegularLessonsCompleted($student)) {
            return redirect()
                ->route('student.modules.show', [$course, $lesson->module])
                ->with('error', 'Завершіть усі уроки модуля перед проходженням підсумкового тесту');
        }

        if (!in_array($lesson->type, [LessonType::Quiz, LessonType::Survey])) {
            return redirect()->route('student.lessons.show', [$course, $lesson]);
        }

        $quiz = $lesson->quiz;

        if (!$quiz) {
            throw new NotFoundHttpException($lesson->type === LessonType::Survey ? 'Опитування не знайдено' : 'Квіз не знайдено');
        }

        $lesson->load(['module']);
        $quiz->load(['questions.answers']);

        $viewModel = new QuizDetailViewModel($quiz, $lesson, $course, $student);

        $draft = ($this->loadQuizDraftAction)($student, $lesson);

        if ($draft) {
            session(['quiz_started_at' => $draft->started_at]);
        } else {
            session(['quiz_started_at' => now()]);
        }

        return view('student.quiz.show', compact('viewModel', 'course', 'lesson', 'quiz', 'draft'));
    }
}
