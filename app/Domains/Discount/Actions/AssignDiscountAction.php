<?php

namespace App\Domains\Discount\Actions;

use App\Domains\Discount\Data\AssignDiscountData;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\Auth;

class AssignDiscountAction
{
    public static function execute(Student $student, AssignDiscountData $data): StudentCourseDiscount
    {
        StudentCourseDiscount::query()
            ->forStudent($student->id)
            ->forCourse($data->course_id)
            ->active()
            ->delete();

        return StudentCourseDiscount::create([
            'student_id' => $student->id,
            'course_id' => $data->course_id,
            'type' => $data->type,
            'value' => $data->value,
            'assigned_by' => Auth::id(),
        ]);
    }
}
