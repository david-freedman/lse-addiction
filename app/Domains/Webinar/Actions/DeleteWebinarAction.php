<?php

namespace App\Domains\Webinar\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Webinar\Models\Webinar;

class DeleteWebinarAction
{
    public static function execute(Webinar $webinar): void
    {
        $webinarTitle = $webinar->title;
        $webinarId = $webinar->id;

        $webinar->delete();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Webinar,
            'subject_id' => $webinarId,
            'activity_type' => ActivityType::WebinarDeleted,
            'description' => 'Webinar deleted',
            'properties' => [
                'title' => $webinarTitle,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
