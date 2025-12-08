<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;

class ReorderLessonsAction
{
    public static function execute(Module $module, array $orderedIds): void
    {
        foreach ($orderedIds as $order => $id) {
            Lesson::where('id', $id)
                ->where('module_id', $module->id)
                ->update(['order' => $order]);
        }
    }
}
