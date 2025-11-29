<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Models\Transaction;

class EnrollStudentAction
{
    public static function execute(Course $course, Student $student, ?Transaction $transaction = null): void
    {
        if ($course->hasStudent($student)) {
            return;
        }

        $pivotData = [
            'enrolled_at' => now(),
            'status' => 'active',
        ];

        if ($transaction) {
            $metadata = $transaction->metadata ?? [];
            $discountId = $metadata['individual_discount_id'] ?? null;

            if ($discountId) {
                $pivotData['individual_discount'] = $metadata['individual_discount_value'] ?? 0;

                StudentCourseDiscount::where('id', $discountId)
                    ->whereNull('used_at')
                    ->update(['used_at' => now()]);
            }
        }

        $course->students()->attach($student->id, $pivotData);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::StudentEnrolled,
            'description' => 'Student enrolled in course',
            'properties' => [
                'course_name' => $course->name,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
                'individual_discount' => $pivotData['individual_discount'] ?? null,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
