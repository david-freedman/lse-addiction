<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Actions\AutoPublishPendingCertificatesAction;
use App\Domains\Course\Data\UpdateCourseData;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseTag;
use Illuminate\Support\Facades\Storage;

class UpdateCourseAction
{
    public static function execute(Course $course, UpdateCourseData $data): Course
    {
        $oldValues = $course->only([
            'name', 'number', 'description', 'price', 'old_price',
            'discount_percentage', 'teacher_id', 'status', 'type',
            'starts_at', 'registration_starts_at', 'registration_ends_at',
            'label', 'requires_certificate_approval', 'banner',
        ]);
        $oldTagNames = $course->tags->pluck('name')->toArray();

        $updateData = [
            'name' => $data->name,
            'number' => $data->number,
            'description' => $data->description,
            'price' => $data->price,
            'old_price' => $data->old_price,
            'discount_percentage' => $data->discount_percentage,
            'teacher_id' => $data->teacher_id,
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

        $updateData['registration_starts_at'] = $data->registration_starts_at;
        $updateData['registration_ends_at'] = $data->registration_ends_at;

        if ($data->label !== null) {
            $updateData['label'] = $data->label;
        }

        $previousApprovalRequired = $course->requires_certificate_approval;
        if ($data->requires_certificate_approval !== null) {
            $updateData['requires_certificate_approval'] = $data->requires_certificate_approval;
        }

        $course->update($updateData);

        $newTagNames = [];
        if ($data->tags !== null) {
            $tagIds = [];
            foreach ($data->tags as $tagName) {
                $tag = CourseTag::findOrCreateByName($tagName);
                $tagIds[] = $tag->id;
                $newTagNames[] = $tag->name;
            }
            $course->tags()->sync($tagIds);
        } else {
            $newTagNames = $oldTagNames;
        }

        $changes = self::computeChanges($oldValues, $updateData);
        $properties = [];
        if (! empty($changes)) {
            $properties['changes'] = $changes;
        }

        $addedTags = array_values(array_diff($newTagNames, $oldTagNames));
        $removedTags = array_values(array_diff($oldTagNames, $newTagNames));
        if (! empty($addedTags) || ! empty($removedTags)) {
            $properties['changes']['tags'] = [
                'added' => $addedTags,
                'removed' => $removedTags,
            ];
        }

        if (! empty($properties)) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Course,
                'subject_id' => $course->id,
                'activity_type' => ActivityType::CourseUpdated,
                'description' => 'Course updated',
                'properties' => $properties,
                'course_id' => $course->id,
            ]));
        }

        if ($previousApprovalRequired && $data->requires_certificate_approval === false) {
            $publisher = auth('admin')->user();
            if ($publisher) {
                app(AutoPublishPendingCertificatesAction::class)($course, $publisher);
            }
        }

        return $course->fresh();
    }

    private static function computeChanges(array $oldValues, array $newValues): array
    {
        $changes = [];
        foreach ($newValues as $key => $newValue) {
            if (! array_key_exists($key, $oldValues)) {
                continue;
            }
            $oldValue = $oldValues[$key];
            if ($oldValue != $newValue) {
                $changes[$key] = ['old' => $oldValue, 'new' => $newValue];
            }
        }

        return $changes;
    }
}
