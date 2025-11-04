<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use Illuminate\Support\Facades\Storage;

class DeleteCourseAction
{
    public static function execute(Course $course): void
    {
        $courseName = $course->name;
        $courseId = $course->id;

        if ($course->banner) {
            Storage::disk('public')->delete($course->banner);
        }

        $course->delete();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $courseId,
            'activity_type' => ActivityType::CourseDeleted,
            'description' => 'Course deleted',
            'properties' => [
                'name' => $courseName,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
