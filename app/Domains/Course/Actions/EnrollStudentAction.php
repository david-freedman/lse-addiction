<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;

class EnrollStudentAction
{
    public static function execute(Course $course, Student $student): void
    {
        if ($course->hasStudent($student)) {
            return;
        }

        $course->students()->attach($student->id, [
            'enrolled_at' => now(),
            'status' => 'active',
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::StudentEnrolled,
            'description' => 'Student enrolled in course',
            'properties' => [
                'course_name' => $course->name,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
