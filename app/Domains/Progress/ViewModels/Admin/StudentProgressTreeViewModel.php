<?php

namespace App\Domains\Progress\ViewModels\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Progress\Models\StudentModuleProgress;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Collection;

readonly class StudentProgressTreeViewModel
{
    public function __construct(
        private ?Course $course = null,
        private ?Student $student = null,
        private ?array $restrictToCourseIds = null,
    ) {}

    public function courses(): Collection
    {
        $query = Course::query()
            ->whereHas('students')
            ->orderBy('name');

        if ($this->restrictToCourseIds !== null) {
            $query->whereIn('id', $this->restrictToCourseIds);
        }

        return $query->get(['id', 'name']);
    }

    public function students(): Collection
    {
        if (!$this->course) {
            return collect();
        }

        return $this->course->students()
            ->orderBy('surname')
            ->orderBy('name')
            ->get(['students.id', 'name', 'surname', 'email']);
    }

    public function selectedCourse(): ?Course
    {
        return $this->course;
    }

    public function selectedStudent(): ?Student
    {
        return $this->student;
    }

    public function hasSelection(): bool
    {
        return $this->course !== null && $this->student !== null;
    }

    public function hasCourseSelection(): bool
    {
        return $this->course !== null;
    }

    public function studentsProgress(): Collection
    {
        if (!$this->course) {
            return collect();
        }

        $students = $this->course->students()
            ->orderBy('surname')
            ->orderBy('name')
            ->get(['students.id', 'name', 'surname', 'email']);

        $progressMap = StudentCourseProgress::query()
            ->where('course_id', $this->course->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return $students->map(fn ($student) => [
            'student' => $student,
            'progress_percentage' => $progressMap->get($student->id)?->progress_percentage ?? 0,
            'status' => $progressMap->get($student->id)?->status ?? ProgressStatus::NotStarted,
            'last_activity_at' => $progressMap->get($student->id)?->updated_at,
        ]);
    }

    public function progressTree(): ?array
    {
        if (!$this->course || !$this->student) {
            return null;
        }

        $courseProgress = StudentCourseProgress::query()
            ->where('course_id', $this->course->id)
            ->where('student_id', $this->student->id)
            ->first();

        $modules = $this->course->modules()
            ->with(['lessons' => fn ($q) => $q->published()->ordered()])
            ->ordered()
            ->get();

        $moduleProgressMap = StudentModuleProgress::query()
            ->where('student_id', $this->student->id)
            ->whereIn('module_id', $modules->pluck('id'))
            ->get()
            ->keyBy('module_id');

        $lessonIds = $modules->flatMap(fn ($m) => $m->lessons->pluck('id'));

        $lessonProgressMap = StudentLessonProgress::query()
            ->where('student_id', $this->student->id)
            ->whereIn('lesson_id', $lessonIds)
            ->get()
            ->keyBy('lesson_id');

        $quizAttemptMap = $this->getQuizAttemptsMap($lessonIds);
        $homeworkSubmissionMap = $this->getHomeworkSubmissionsMap($lessonIds);

        $modulesData = $modules->map(function ($module) use ($moduleProgressMap, $lessonProgressMap, $quizAttemptMap, $homeworkSubmissionMap) {
            $moduleProgress = $moduleProgressMap->get($module->id);

            $lessonsData = $module->lessons->map(function ($lesson) use ($lessonProgressMap, $quizAttemptMap, $homeworkSubmissionMap) {
                $lessonProgress = $lessonProgressMap->get($lesson->id);

                return [
                    'lesson' => $lesson,
                    'status' => $lessonProgress?->status ?? ProgressStatus::NotStarted,
                    'completed_at' => $lessonProgress?->completed_at,
                    'quiz' => $this->buildQuizData($lesson, $quizAttemptMap),
                    'homework' => $this->buildHomeworkData($lesson, $homeworkSubmissionMap),
                ];
            });

            return [
                'module' => $module,
                'progress_percentage' => $moduleProgress?->progress_percentage ?? 0,
                'status' => $moduleProgress?->status ?? ProgressStatus::NotStarted,
                'lessons' => $lessonsData,
            ];
        });

        return [
            'course' => $this->course,
            'progress_percentage' => $courseProgress?->progress_percentage ?? 0,
            'status' => $courseProgress?->status ?? ProgressStatus::NotStarted,
            'modules' => $modulesData,
        ];
    }

    private function getQuizAttemptsMap(Collection $lessonIds): Collection
    {
        return StudentQuizAttempt::query()
            ->where('student_id', $this->student->id)
            ->whereHas('quiz', function ($q) use ($lessonIds) {
                $q->where('quizzable_type', 'App\\Domains\\Lesson\\Models\\Lesson')
                    ->whereIn('quizzable_id', $lessonIds);
            })
            ->with('quiz')
            ->orderByDesc('attempt_number')
            ->get()
            ->groupBy(fn ($attempt) => $attempt->quiz->quizzable_id)
            ->map(fn ($attempts) => $attempts->first());
    }

    private function getHomeworkSubmissionsMap(Collection $lessonIds): Collection
    {
        return HomeworkSubmission::query()
            ->where('student_id', $this->student->id)
            ->whereHas('homework', fn ($q) => $q->whereIn('lesson_id', $lessonIds))
            ->with('homework')
            ->orderByDesc('attempt_number')
            ->get()
            ->groupBy(fn ($sub) => $sub->homework->lesson_id)
            ->map(fn ($subs) => $subs->first());
    }

    private function buildQuizData($lesson, Collection $quizAttemptMap): array
    {
        $quiz = $lesson->quiz;
        $attempt = $quizAttemptMap->get($lesson->id);

        return [
            'exists' => $quiz !== null,
            'quiz_id' => $quiz?->id,
            'score' => $attempt?->score,
            'max_score' => $attempt?->max_score,
            'passed' => $attempt?->passed,
            'attempt_id' => $attempt?->id,
            'score_percentage' => $attempt?->score_percentage,
        ];
    }

    private function buildHomeworkData($lesson, Collection $homeworkSubmissionMap): array
    {
        $homework = $lesson->homework;
        $submission = $homeworkSubmissionMap->get($lesson->id);

        return [
            'exists' => $homework !== null,
            'homework_id' => $homework?->id,
            'is_required' => $homework?->is_required ?? false,
            'status' => $submission?->status,
            'submission_id' => $submission?->id,
            'score' => $submission?->score,
            'max_points' => $homework?->max_points,
        ];
    }
}
