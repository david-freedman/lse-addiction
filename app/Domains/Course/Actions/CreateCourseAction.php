<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Data\CreateCourseData;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseTag;
use Illuminate\Support\Str;

class CreateCourseAction
{
    public static function execute(CreateCourseData $data): Course
    {
        $bannerPath = null;
        if ($data->banner) {
            $bannerPath = $data->banner->store('courses', 'public');
        }

        $slug = self::generateUniqueSlug($data->name);

        $course = Course::create([
            'name' => $data->name,
            'slug' => $slug,
            'number' => $data->number,
            'description' => $data->description,
            'price' => $data->price,
            'old_price' => $data->old_price,
            'discount_percentage' => $data->discount_percentage,
            'teacher_id' => $data->teacher_id,
            'banner' => $bannerPath,
            'author_id' => auth()->id(),
            'status' => $data->status,
            'type' => $data->type,
            'starts_at' => $data->starts_at,
            'registration_starts_at' => $data->registration_starts_at,
            'registration_ends_at' => $data->registration_ends_at,
            'label' => $data->label,
            'requires_certificate_approval' => $data->requires_certificate_approval,
        ]);

        if ($data->tags) {
            $tagIds = [];
            foreach ($data->tags as $tagName) {
                $tag = CourseTag::findOrCreateByName($tagName);
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
                'attributes' => array_filter([
                    'name' => $data->name,
                    'number' => $data->number,
                    'description' => $data->description,
                    'price' => $data->price,
                    'old_price' => $data->old_price,
                    'discount_percentage' => $data->discount_percentage,
                    'teacher_id' => $data->teacher_id,
                    'status' => $data->status,
                    'type' => $data->type,
                    'starts_at' => $data->starts_at,
                    'label' => $data->label,
                    'tags' => $data->tags,
                ], fn ($v) => $v !== null),
            ],
            'course_id' => $course->id,
        ]));

        return $course;
    }

    private static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
