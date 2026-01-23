<?php

namespace App\Domains\Module\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Module\Data\UpdateModuleData;
use App\Domains\Module\Models\Module;

class UpdateModuleAction
{
    public static function execute(Module $module, UpdateModuleData $data): Module
    {
        $oldValues = $module->only(['name', 'description', 'order', 'status', 'unlock_rule']);

        $updateData = ['name' => $data->name];

        if ($data->description !== null) {
            $updateData['description'] = $data->description;
        }

        if ($data->order !== null) {
            $updateData['order'] = $data->order;
        }

        if ($data->status !== null) {
            $updateData['status'] = $data->status;
        }

        if ($data->unlock_rule !== null) {
            $updateData['unlock_rule'] = $data->unlock_rule;
        }

        $module->update($updateData);

        $changes = [];
        foreach ($updateData as $key => $newValue) {
            if (array_key_exists($key, $oldValues) && $oldValues[$key] != $newValue) {
                $changes[$key] = ['old' => $oldValues[$key], 'new' => $newValue];
            }
        }

        if (! empty($changes)) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $module->id,
                'activity_type' => ActivityType::ModuleUpdated,
                'description' => 'Module updated',
                'properties' => ['changes' => $changes],
                'course_id' => $module->course_id,
            ]));
        }

        return $module->fresh();
    }
}
