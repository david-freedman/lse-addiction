<?php

namespace App\Domains\Discount\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Student\Models\Student;

class GetActiveDiscountAction
{
    public static function execute(Course $course, Student $student): ?StudentCourseDiscount
    {
        return StudentCourseDiscount::query()
            ->forStudent($student->id)
            ->forCourse($course->id)
            ->active()
            ->first();
    }
}
