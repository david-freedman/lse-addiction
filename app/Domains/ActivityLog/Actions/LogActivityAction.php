<?php

namespace App\Domains\ActivityLog\Actions;

use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Models\ActivityLog;

class LogActivityAction
{
    public static function execute(ActivityLogData $data): ActivityLog
    {
        return ActivityLog::create([
            'subject_type' => $data->subject_type,
            'subject_id' => $data->subject_id,
            'activity_type' => $data->activity_type,
            'description' => $data->description,
            'properties' => $data->properties,
            'ip_address' => $data->ip_address ?? request()->ip(),
            'user_agent' => $data->user_agent ?? request()->userAgent(),
        ]);
    }
}
