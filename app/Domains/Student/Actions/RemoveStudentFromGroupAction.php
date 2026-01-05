<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentGroup;

class RemoveStudentFromGroupAction
{
    public static function execute(StudentGroup $group, Student $student): void
    {
        $group->students()->detach($student->id);
    }
}
