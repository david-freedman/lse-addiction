<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;

class ReorderLessonsAction
{
    public static function execute(Module $module, array $orderedIds): void
    {
        $oldOrder = $module->lessons()->orderBy('order')->get(['id', 'name'])->map(
            fn (Lesson $l) => ['id' => $l->id, 'name' => $l->name]
        )->values()->toArray();

        foreach ($orderedIds as $order => $id) {
            Lesson::where('id', $id)
                ->where('module_id', $module->id)
                ->update(['order' => $order]);
        }

        $newOrder = $module->lessons()->orderBy('order')->get(['id', 'name'])->map(
            fn (Lesson $l) => ['id' => $l->id, 'name' => $l->name]
        )->values()->toArray();

        if ($oldOrder !== $newOrder) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $module->id,
                'activity_type' => ActivityType::LessonsReordered,
                'description' => 'Lessons reordered',
                'properties' => [
                    'reorder' => ['old' => $oldOrder, 'new' => $newOrder],
                ],
                'course_id' => $module->course_id,
            ]));
        }
    }
}
