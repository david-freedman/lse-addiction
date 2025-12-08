<?php

namespace App\Domains\Module\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Data\CreateModuleData;
use App\Domains\Module\Models\Module;

class CreateModuleAction
{
    public static function execute(Course $course, CreateModuleData $data): Module
    {
        return $course->modules()->create([
            'name' => $data->name,
            'description' => $data->description,
            'order' => $data->order ?: $course->modules()->max('order') + 1,
            'status' => $data->status,
            'has_final_test' => $data->has_final_test,
            'unlock_rule' => $data->unlock_rule,
        ]);
    }
}
