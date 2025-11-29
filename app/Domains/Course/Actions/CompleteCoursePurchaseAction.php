<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Transaction\Models\Transaction;

class CompleteCoursePurchaseAction
{
    public static function execute(Transaction $transaction): void
    {
        $course = $transaction->purchasable;
        $student = $transaction->student;

        if ($course->hasStudent($student)) {
            return;
        }

        EnrollStudentAction::execute($course, $student, $transaction);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CoursePurchased,
            'description' => 'Course purchased via payment',
            'properties' => [
                'course_name' => $course->name,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
                'amount' => $transaction->amount,
                'transaction_number' => $transaction->transaction_number,
                'payment_provider' => $transaction->payment_provider?->value,
                'payment_method' => $transaction->payment_method?->value,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
