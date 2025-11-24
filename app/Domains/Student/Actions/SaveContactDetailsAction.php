<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\ContactDetailsData;
use App\Domains\Student\Models\Student;

class SaveContactDetailsAction
{
    public static function execute(Student $student, ContactDetailsData $data): Student
    {
        $student->update([
            'name' => $data->name,
            'surname' => $data->surname,
            'birthday' => $data->birthday,
            'city' => $data->city,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentContactDetailsAdded,
            'description' => 'Student contact details saved during registration',
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
