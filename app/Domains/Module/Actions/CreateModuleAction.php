<?php

namespace App\Domains\Module\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Module\Data\CreateModuleData;
use App\Domains\Module\Models\Module;

class CreateModuleAction
{
    public static function execute(Course $course, CreateModuleData $data): Module
    {
        $module = $course->modules()->create([
            'name' => $data->name,
            'description' => $data->description,
            'order' => $data->order ?? $course->modules()->count(),
            'status' => $data->status,
            'unlock_rule' => $data->unlock_rule,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Module,
            'subject_id' => $module->id,
            'activity_type' => ActivityType::ModuleCreated,
            'description' => 'Module created',
            'properties' => [
                'attributes' => array_filter([
                    'name' => $data->name,
                    'description' => $data->description,
                    'status' => $data->status,
                    'unlock_rule' => $data->unlock_rule,
                ], fn ($v) => $v !== null),
            ],
            'course_id' => $course->id,
        ]));

        return $module;
    }
}
