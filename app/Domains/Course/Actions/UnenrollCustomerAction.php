<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Customer\Models\Customer;

class UnenrollCustomerAction
{
    public static function execute(Course $course, Customer $customer): void
    {
        $course->customers()->updateExistingPivot($customer->id, [
            'status' => 'cancelled',
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CustomerUnenrolled,
            'description' => 'Customer unenrolled from course',
            'properties' => [
                'course_name' => $course->name,
                'customer_id' => $customer->id,
                'customer_email' => $customer->email->value,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
