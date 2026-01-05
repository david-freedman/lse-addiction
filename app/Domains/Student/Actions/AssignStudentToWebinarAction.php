<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;

class AssignStudentToWebinarAction
{
    public static function execute(Student $student, Webinar $webinar): bool
    {
        if ($webinar->isRegistered($student)) {
            return false;
        }

        $webinar->students()->attach($student->id, [
            'registered_at' => now(),
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentAssignedToWebinar,
            'description' => 'Student assigned to webinar by admin',
            'properties' => [
                'webinar_id' => $webinar->id,
                'webinar_title' => $webinar->title,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return true;
    }
}
