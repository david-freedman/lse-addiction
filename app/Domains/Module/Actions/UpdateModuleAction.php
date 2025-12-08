<?php

namespace App\Domains\Module\Actions;

use App\Domains\Module\Data\UpdateModuleData;
use App\Domains\Module\Models\Module;

class UpdateModuleAction
{
    public static function execute(Module $module, UpdateModuleData $data): Module
    {
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

        if ($data->has_final_test !== null) {
            $updateData['has_final_test'] = $data->has_final_test;
        }

        if ($data->unlock_rule !== null) {
            $updateData['unlock_rule'] = $data->unlock_rule;
        }

        $module->update($updateData);

        return $module->fresh();
    }
}
