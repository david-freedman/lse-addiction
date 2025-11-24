<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\UpdatePersonalDetailsData;
use App\Domains\Student\Models\Student;

class UpdateStudentPersonalDetailsAction
{
    public static function execute(Student $student, UpdatePersonalDetailsData $data): Student
    {
        $student->update([
            'surname' => $data->surname,
            'name' => $data->name,
            'birthday' => $data->birthday,
            'city' => $data->city,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentPersonalDetailsUpdated,
            'description' => 'Student personal details updated',
            'properties' => [
                'name' => $data->name,
                'surname' => $data->surname,
                'city' => $data->city,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $student->fresh();
    }
}
