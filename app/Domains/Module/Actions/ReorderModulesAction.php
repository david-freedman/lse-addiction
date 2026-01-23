<?php

namespace App\Domains\Module\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Module\Models\Module;

class ReorderModulesAction
{
    public static function execute(Course $course, array $orderedIds): void
    {
        $oldOrder = $course->modules()->orderBy('order')->get(['id', 'name'])->map(
            fn (Module $m) => ['id' => $m->id, 'name' => $m->name]
        )->values()->toArray();

        foreach ($orderedIds as $order => $id) {
            Module::where('id', $id)
                ->where('course_id', $course->id)
                ->update(['order' => $order]);
        }

        $newOrder = $course->modules()->orderBy('order')->get(['id', 'name'])->map(
            fn (Module $m) => ['id' => $m->id, 'name' => $m->name]
        )->values()->toArray();

        if ($oldOrder !== $newOrder) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Course,
                'subject_id' => $course->id,
                'activity_type' => ActivityType::ModulesReordered,
                'description' => 'Modules reordered',
                'properties' => [
                    'reorder' => ['old' => $oldOrder, 'new' => $newOrder],
                ],
                'course_id' => $course->id,
            ]));
        }
    }
}
