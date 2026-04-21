<?php

namespace App\Domains\Student\Actions;

use App\Domains\Course\Enums\CourseStudentStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Support\Facades\Log;

class EnrollFromSessionAction
{
    public static function execute(Student $student): void
    {
        $externalId = session('pending_course_id');

        if (! $externalId || ! preg_match('/^[cw]\d+$/', $externalId)) {
            return;
        }

        $prefix = $externalId[0];
        $id = (int) substr($externalId, 1);

        try {
            if ($prefix === 'c') {
                self::enrollInCourse($student, $id);
            } else {
                self::enrollInWebinar($student, $id);
            }
        } catch (\Throwable $e) {
            Log::error("[EnrollFromSession] Failed to enroll student {$student->id} in {$externalId}: {$e->getMessage()}");
        }

        session()->forget('pending_course_id');
    }

    private static function enrollInCourse(Student $student, int $courseId): void
    {
        $course = Course::find($courseId);

        if (! $course) {
            Log::warning("[EnrollFromSession] Course {$courseId} not found");
            return;
        }

        if ($student->courses()->where('course_id', $course->id)->exists()) {
            return;
        }

        $student->courses()->attach($course->id, [
            'status'      => CourseStudentStatus::Active->value,
            'enrolled_at' => now(),
            'notes'       => json_encode(['source' => 'wp_redirect'], JSON_UNESCAPED_UNICODE),
        ]);

        Log::info("[EnrollFromSession] Student {$student->id} enrolled in course {$courseId}");
    }

    private static function enrollInWebinar(Student $student, int $webinarId): void
    {
        $webinar = Webinar::find($webinarId);

        if (! $webinar) {
            Log::warning("[EnrollFromSession] Webinar {$webinarId} not found");
            return;
        }

        if ($webinar->students()->where('student_id', $student->id)->whereNull('webinar_student.cancelled_at')->exists()) {
            return;
        }

        $webinar->students()->attach($student->id, [
            'registered_at' => now(),
        ]);

        Log::info("[EnrollFromSession] Student {$student->id} enrolled in webinar {$webinarId}");
    }
}
