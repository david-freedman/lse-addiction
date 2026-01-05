<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;

class UnregisterStudentFromWebinarAction
{
    public static function execute(Student $student, Webinar $webinar): void
    {
        $student->webinars()->detach($webinar->id);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentUnregisteredFromWebinar,
            'description' => 'Student unregistered from webinar by admin',
            'properties' => [
                'webinar_id' => $webinar->id,
                'webinar_title' => $webinar->title,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
