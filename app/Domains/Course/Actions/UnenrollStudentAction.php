<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;

class UnenrollStudentAction
{
    public static function execute(Course $course, Student $student): void
    {
        if ($student->hasPurchasedCourse($course)) {
            throw new \Exception('Неможливо відписатись від придбаного курсу');
        }

        $course->students()->updateExistingPivot($student->id, [
            'status' => 'cancelled',
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::StudentUnenrolled,
            'description' => 'Student unenrolled from course',
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
