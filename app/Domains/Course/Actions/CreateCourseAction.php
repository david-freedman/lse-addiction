<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Data\CreateCourseData;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Tag;

class CreateCourseAction
{
    public static function execute(CreateCourseData $data): Course
    {
        $bannerPath = null;
        if ($data->banner) {
            $bannerPath = $data->banner->store('courses', 'public');
        }

        $course = Course::create([
            'name' => $data->name,
            'description' => $data->description,
            'price' => $data->price,
            'coach_id' => $data->coach_id,
            'banner' => $bannerPath,
            'author_id' => $data->author_id,
            'status' => $data->status,
        ]);

        if ($data->tags) {
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
            'activity_type' => ActivityType::CourseCreated,
            'description' => 'Course created',
            'properties' => [
                'name' => $data->name,
                'status' => $data->status,
                'price' => $data->price,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $course;
    }
}
