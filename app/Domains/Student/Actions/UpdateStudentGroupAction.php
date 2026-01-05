<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\UpdateStudentGroupData;
use App\Domains\Student\Models\StudentGroup;

class UpdateStudentGroupAction
{
    public static function execute(StudentGroup $group, UpdateStudentGroupData $data): StudentGroup
    {
        $group->update([
            'name' => $data->name,
            'description' => $data->description,
            'course_id' => $data->course_id,
        ]);

        return $group->fresh();
    }
}
