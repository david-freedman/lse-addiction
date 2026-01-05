<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Enums\CourseStudentStatus;
use App\Domains\Student\Data\AssignToCourseData;
use App\Domains\Student\Models\Student;

class AssignStudentToCoursesAction
{
    public static function execute(Student $student, array $assignments): void
    {
        foreach ($assignments as $assignment) {
            if (! $assignment instanceof AssignToCourseData) {
                continue;
            }

            if ($student->courses()->where('course_id', $assignment->course_id)->exists()) {
                continue;
            }

            $student->courses()->attach($assignment->course_id, [
                'status' => CourseStudentStatus::Active->value,
                'teacher_id' => $assignment->teacher_id,
                'individual_discount' => $assignment->individual_discount ?? 0,
                'notes' => $assignment->notes,
                'enrolled_at' => now(),
            ]);

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => $student->id,
                'activity_type' => ActivityType::StudentAssignedToCourse,
                'description' => 'Student assigned to course by admin',
                'properties' => [
                    'course_id' => $assignment->course_id,
                    'teacher_id' => $assignment->teacher_id,
                    'individual_discount' => $assignment->individual_discount,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]));
        }
    }
}
