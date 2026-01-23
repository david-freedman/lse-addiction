<?php

namespace App\Domains\Module\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Module\Models\Module;

class DeleteModuleAction
{
    public static function execute(Module $module): bool
    {
        $courseId = $module->course_id;
        $attributes = $module->only(['id', 'name', 'status']);

        $result = $module->delete();

        if ($result) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $attributes['id'],
                'activity_type' => ActivityType::ModuleDeleted,
                'description' => 'Module deleted',
                'properties' => ['attributes' => $attributes],
                'course_id' => $courseId,
            ]));
        }

        return $result;
    }
}
