<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\CreateStudentData;
use App\Domains\Student\Models\Student;

class CreateStudentAction
{
    public static function execute(CreateStudentData $data): Student
    {
        $attributes = [
            'email' => $data->email,
            'phone' => $data->phone,
            'name' => $data->name,
            'surname' => $data->surname,
            'birthday' => $data->birthday,
            'city' => $data->city,
        ];

        if ($data->profile_photo) {
            $attributes['profile_photo'] = $data->profile_photo->store('students/photos', 'public');
        }

        if ($data->email_verified) {
            $attributes['email_verified_at'] = now();
        }

        if ($data->phone_verified) {
            $attributes['phone_verified_at'] = now();
        }

        $student = Student::create($attributes);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentCreatedByAdmin,
            'description' => 'Student created by admin',
            'properties' => [
                'name' => $student->name,
                'surname' => $student->surname,
                'email' => $student->email->value,
                'phone' => $student->phone->value,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $student;
    }
}
