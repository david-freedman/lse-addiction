<?php

namespace App\Domains\Quiz\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Lesson\ViewModels\LessonDetailViewModel;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class QuizDetailViewModel
{
    private LessonDetailViewModel $lessonViewModel;

    public function __construct(
        private Quiz $quiz,
        private Lesson $lesson,
        private Course $course,
        private Student $student
    ) {
        $this->lessonViewModel = new LessonDetailViewModel($lesson, $course, $student);
    }

    public function quizId(): int
    {
        return $this->quiz->id;
    }

    public function quizTitle(): string
    {
        return $this->quiz->title ?? $this->lesson->name;
    }

    public function isSurvey(): bool
    {
        return $this->quiz->isSurvey();
    }

    public function title(): string
    {
        return $this->isSurvey() ? 'Опитування' : 'Квіз';
    }

    public function questions(): Collection
    {
        return $this->quiz->questions()
            ->with('answers')
            ->orderBy('order')
            ->get();
    }

    public function passingScore(): int
    {
        return $this->quiz->passing_score ?? 70;
    }

    public function maxAttempts(): ?int
    {
        return $this->quiz->max_attempts;
    }

    public function timeLimit(): ?int
    {
        return $this->quiz->time_limit_minutes;
    }

    public function showCorrectAnswers(): bool
    {
        return $this->quiz->show_correct_answers ?? false;
    }

    public function attemptsUsed(): int
    {
        return $this->student->quizAttempts()
            ->where('quiz_id', $this->quiz->id)
            ->count();
    }

    public function attemptsRemaining(): ?int
    {
        if (! $this->quiz->max_attempts) {
            return null;
        }

        return max(0, $this->quiz->max_attempts - $this->attemptsUsed());
    }

    public function canAttempt(): bool
    {
        if (! $this->quiz->max_attempts) {
            return true;
        }

        return $this->attemptsUsed() < $this->quiz->max_attempts;
    }

    public function bestAttempt(): ?StudentQuizAttempt
    {
        return $this->student->quizAttempts()
            ->where('quiz_id', $this->quiz->id)
            ->orderByDesc('score')
            ->first();
    }

    public function lastAttempt(): ?StudentQuizAttempt
    {
        return $this->student->quizAttempts()
            ->where('quiz_id', $this->quiz->id)
            ->latest()
            ->first();
    }

    public function bestScore(): ?int
    {
        return $this->bestAttempt()?->score;
    }

    public function bestScorePercentage(): ?int
    {
        $attempt = $this->bestAttempt();

        if (!$attempt || (float) $attempt->max_score === 0.0) {
            return null;
        }

        return (int) round(($attempt->score / $attempt->max_score) * 100);
    }

    public function hasPassed(): bool
    {
        return $this->student->quizAttempts()
            ->where('quiz_id', $this->quiz->id)
            ->where('passed', true)
            ->exists();
    }

    public function lessonId(): int
    {
        return $this->lessonViewModel->lessonId();
    }

    public function lessonName(): string
    {
        return $this->lessonViewModel->lessonName();
    }

    public function moduleName(): string
    {
        return $this->lessonViewModel->moduleName();
    }

    public function moduleNumber(): int
    {
        return $this->lessonViewModel->moduleNumber();
    }

    public function lessonNumber(): int
    {
        return $this->lessonViewModel->lessonNumber();
    }

    public function totalLessonsInModule(): int
    {
        return $this->lessonViewModel->totalLessonsInModule();
    }

    public function duration(): string
    {
        return $this->lessonViewModel->duration();
    }

    public function description(): string
    {
        return $this->lessonViewModel->description();
    }

    public function courseProgressPercent(): int
    {
        return $this->lessonViewModel->courseProgressPercent();
    }

    public function isCompleted(): bool
    {
        return $this->lessonViewModel->isCompleted();
    }

    public function previousLessonUrl(): ?string
    {
        return $this->lessonViewModel->previousLessonUrl();
    }

    public function nextLessonUrl(): ?string
    {
        return $this->lessonViewModel->nextLessonUrl();
    }

    public function canNavigateToPrevious(): bool
    {
        return $this->lessonViewModel->canNavigateToPrevious();
    }

    public function canNavigateToNext(): bool
    {
        return $this->lessonViewModel->canNavigateToNext();
    }

    public function modules(): Collection
    {
        return $this->lessonViewModel->modules();
    }

    public function courseName(): string
    {
        return $this->lessonViewModel->courseName();
    }

    public function courseId(): int
    {
        return $this->lessonViewModel->courseId();
    }

    public function teacherName(): string
    {
        return $this->lessonViewModel->teacherName();
    }

    public function backToCourseUrl(): string
    {
        return $this->lessonViewModel->backToCourseUrl();
    }

    public function courseUrl(): string
    {
        return $this->lessonViewModel->courseUrl();
    }

    public function backToModuleUrl(): string
    {
        return $this->lessonViewModel->backToModuleUrl();
    }

    public function submitUrl(): string
    {
        return route('student.quiz.submit', [$this->course, $this->lesson]);
    }

    public function categories(): array
    {
        $categories = [];

        foreach ($this->quiz->questions as $question) {
            foreach ($question->answers as $answer) {
                if ($answer->category && ! in_array($answer->category, $categories)) {
                    $categories[] = $answer->category;
                }
            }
        }

        return $categories;
    }
}
