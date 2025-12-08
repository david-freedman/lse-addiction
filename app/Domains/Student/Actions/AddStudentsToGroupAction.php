<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\AddStudentsToGroupData;
use App\Domains\Student\Models\StudentGroup;

class AddStudentsToGroupAction
{
    public static function execute(StudentGroup $group, AddStudentsToGroupData $data): void
    {
        $pivotData = [];
        foreach ($data->student_ids as $studentId) {
            $pivotData[$studentId] = ['added_at' => now()];
        }

        $group->students()->syncWithoutDetaching($pivotData);
    }
}
