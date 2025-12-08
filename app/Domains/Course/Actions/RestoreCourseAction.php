<?php

namespace App\Domains\Course\Actions;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;

class RestoreCourseAction
{
    public static function execute(Course $course, CourseStatus $newStatus = CourseStatus::Active): Course
    {
        $course->update(['status' => $newStatus]);

        return $course;
    }
}
