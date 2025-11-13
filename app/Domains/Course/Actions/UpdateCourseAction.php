<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Data\UpdateCourseData;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Tag;
use Illuminate\Support\Facades\Storage;

class UpdateCourseAction
{
    public static function execute(Course $course, UpdateCourseData $data): Course
    {
        $updateData = [
            'name' => $data->name,
            'description' => $data->description,
            'price' => $data->price,
            'coach_id' => $data->coach_id,
            'status' => $data->status,
        ];

        if ($data->banner) {
            if ($course->banner) {
                Storage::disk('public')->delete($course->banner);
            }
            $updateData['banner'] = $data->banner->store('courses', 'public');
        }

        if ($data->type !== null) {
            $updateData['type'] = $data->type;
        }

        if ($data->starts_at !== null) {
            $updateData['starts_at'] = $data->starts_at;
        }

        if ($data->label !== null) {
            $updateData['label'] = $data->label;
        }

        if ($data->author_id !== null) {
            $updateData['author_id'] = $data->author_id;
        }

        $course->update($updateData);

        if ($data->tags !== null) {
            $tagIds = [];
            foreach ($data->tags as $tagName) {
                $tag = Tag::findOrCreateByName($tagName);
                $tagIds[] = $tag->id;
            }
            $course->tags()->sync($tagIds);
        }

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseUpdated,
            'description' => 'Course updated',
            'properties' => [
                'name' => $data->name,
                'status' => $data->status,
                'price' => $data->price,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $course->fresh();
    }
}
