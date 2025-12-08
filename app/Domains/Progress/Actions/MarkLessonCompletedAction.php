<?php

namespace App\Domains\Progress\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Student\Models\Student;

final class MarkLessonCompletedAction
{
    public function __invoke(Student $student, Lesson $lesson, Course $course): StudentLessonProgress
    {
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

        if (! $lessonProgress->started_at) {
            $lessonProgress->update(['started_at' => now()]);
        }

        $this->updateCourseProgress($student, $course);

        return $lessonProgress;
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
    }
}
