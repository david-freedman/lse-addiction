<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;

class UnenrollStudentFromCourseAction
{
    public static function execute(Student $student, Course $course): void
    {
        $student->courses()->detach($course->id);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentUnenrolledFromCourse,
            'description' => 'Student unenrolled from course by admin',
            'properties' => [
                'course_id' => $course->id,
                'course_name' => $course->name,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
