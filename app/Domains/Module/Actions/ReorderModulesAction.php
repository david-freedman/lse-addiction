<?php

namespace App\Domains\Module\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Models\Module;

class ReorderModulesAction
{
    public static function execute(Course $course, array $orderedIds): void
    {
        foreach ($orderedIds as $order => $id) {
            Module::where('id', $id)
                ->where('course_id', $course->id)
                ->update(['order' => $order]);
        }
    }
}
