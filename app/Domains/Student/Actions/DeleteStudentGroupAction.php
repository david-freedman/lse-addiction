<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Models\StudentGroup;

class DeleteStudentGroupAction
{
    public static function execute(StudentGroup $group): void
    {
        $group->students()->detach();
        $group->delete();
    }
}
