<?php

namespace App\Domains\Progress\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Actions\IssueCertificateAction;
use App\Domains\Course\Models\Course;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Progress\Models\StudentModuleProgress;
use App\Domains\Student\Models\Student;

final class MarkLessonCompletedAction
{
    public function __invoke(Student $student, Lesson $lesson, Course $course): StudentLessonProgress
    {
        if (!$this->canMarkCompleted($student, $lesson)) {
            $lessonProgress = StudentLessonProgress::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'status' => ProgressStatus::InProgress,
                    'started_at' => now(),
                ]
            );

            return $lessonProgress;
        }

        $lessonProgress = StudentLessonProgress::updateOrCreate(
            [
                'student_id' => $student->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'status' => ProgressStatus::Completed,
                'completed_at' => now(),
            ]
        );

        if (!$lessonProgress->started_at) {
            $lessonProgress->update(['started_at' => now()]);
        }

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Lesson,
            'subject_id' => $lesson->id,
            'activity_type' => ActivityType::LessonCompleted,
            'description' => 'Lesson completed',
            'properties' => [
                'lesson_id' => $lesson->id,
                'lesson_title' => $lesson->title,
                'lesson_type' => $lesson->type->value,
                'module_id' => $lesson->module_id,
                'course_id' => $course->id,
                'course_name' => $course->name,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        $this->updateModuleProgress($student, $lesson->module);
        $this->updateCourseProgress($student, $course);

        return $lessonProgress;
    }

    private function updateModuleProgress(Student $student, Module $module): void
    {
        $totalLessons = $module->lessons()->published()->count();

        if ($totalLessons === 0) {
            return;
        }

        $lessonIds = $module->lessons()->published()->pluck('id');

        $completedLessons = StudentLessonProgress::where('student_id', $student->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', ProgressStatus::Completed)
            ->count();

        $progressPercentage = ($completedLessons / $totalLessons) * 100;

        $status = match (true) {
            $progressPercentage >= 100 => ProgressStatus::Completed,
            $progressPercentage > 0 => ProgressStatus::InProgress,
            default => ProgressStatus::NotStarted,
        };

        StudentModuleProgress::updateOrCreate(
            [
                'student_id' => $student->id,
                'module_id' => $module->id,
            ],
            [
                'status' => $status,
                'lessons_completed' => $completedLessons,
                'total_lessons' => $totalLessons,
                'progress_percentage' => min($progressPercentage, 100),
                'started_at' => now(),
                'completed_at' => $status === ProgressStatus::Completed ? now() : null,
            ]
        );
    }

    private function updateCourseProgress(Student $student, Course $course): void
    {
        $totalLessons = $course->modules()
            ->active()
            ->withCount(['lessons' => function ($query) {
                $query->published();
            }])
            ->get()
            ->sum('lessons_count');

        if ($totalLessons === 0) {
            return;
        }

        $lessonIds = $course->modules()
            ->active()
            ->with(['lessons' => function ($query) {
                $query->published();
            }])
            ->get()
            ->flatMap(fn ($module) => $module->lessons->pluck('id'));

        $completedLessons = StudentLessonProgress::where('student_id', $student->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', ProgressStatus::Completed)
            ->count();

        $progressPercentage = ($completedLessons / $totalLessons) * 100;

        $status = match (true) {
            $progressPercentage >= 100 => ProgressStatus::Completed,
            $progressPercentage > 0 => ProgressStatus::InProgress,
            default => ProgressStatus::NotStarted,
        };

        StudentCourseProgress::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course->id,
            ],
            [
                'status' => $status,
                'progress_percentage' => min($progressPercentage, 100),
                'started_at' => now(),
                'completed_at' => $status === ProgressStatus::Completed ? now() : null,
            ]
        );

        if ($status === ProgressStatus::Completed && $course->hasFinalQuiz()) {
            app(IssueCertificateAction::class)($student, $course);
        }
    }

    private function canMarkCompleted(Student $student, Lesson $lesson): bool
    {
        if (!$lesson->hasRequiredHomework()) {
            return true;
        }

        $homework = $lesson->homework;
        $latestSubmission = $homework->getLatestSubmission($student->id);

        if (!$latestSubmission) {
            return false;
        }

        if ($latestSubmission->status !== HomeworkSubmissionStatus::Approved) {
            return false;
        }

        $passingPoints = $homework->getPassingScorePoints();

        if ($passingPoints !== null && $latestSubmission->score < $passingPoints) {
            return false;
        }

        return true;
    }
}
