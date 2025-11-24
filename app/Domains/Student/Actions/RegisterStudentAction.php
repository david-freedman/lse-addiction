<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\RegisterStudentData;
use App\Domains\Student\Models\Student;

class RegisterStudentAction
{
    public static function execute(RegisterStudentData $data): Student
    {
        $student = Student::create([
            'email' => $data->email,
            'phone' => $data->phone,
        ]);

        SendVerificationCodeAction::execute(
            type: 'phone',
            contact: $student->phone->value,
            purpose: 'registration',
            studentId: $student->id
        );

        SendVerificationCodeAction::execute(
            type: 'email',
            contact: $student->email->value,
            purpose: 'registration',
            studentId: $student->id
        );

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentRegistered,
            'description' => 'Student registered successfully',
            'properties' => [
                'email' => $student->email->value,
                'phone' => $student->phone->value,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $student;
    }
}
