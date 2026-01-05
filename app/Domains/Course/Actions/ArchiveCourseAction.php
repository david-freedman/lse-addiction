<?php

namespace App\Domains\Course\Actions;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;

class ArchiveCourseAction
{
    public static function execute(Course $course): Course
    {
        $course->update(['status' => CourseStatus::Archived]);

        return $course;
    }
}
