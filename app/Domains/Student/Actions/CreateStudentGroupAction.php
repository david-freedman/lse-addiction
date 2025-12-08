<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\CreateStudentGroupData;
use App\Domains\Student\Models\StudentGroup;
use App\Models\User;

class CreateStudentGroupAction
{
    public static function execute(CreateStudentGroupData $data, User $creator): StudentGroup
    {
        return StudentGroup::create([
            'name' => $data->name,
            'description' => $data->description,
            'course_id' => $data->course_id,
            'created_by' => $creator->id,
        ]);
    }
}
