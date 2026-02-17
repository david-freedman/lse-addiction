<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Models\Lesson;

class DeleteLessonAction
{
    public static function execute(Lesson $lesson): bool
    {
        $courseId = $lesson->module->course_id;
        $attributes = $lesson->only(['id', 'name', 'type', 'status']);
        $attributes['type'] = $lesson->type?->value;

        $lesson->quiz?->delete();

        $result = $lesson->delete();

        if ($result) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Lesson,
                'subject_id' => $attributes['id'],
                'activity_type' => ActivityType::LessonDeleted,
                'description' => 'Lesson deleted',
                'properties' => ['attributes' => $attributes],
                'course_id' => $courseId,
            ]));
        }

        return $result;
    }
}
