<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\UpdateContactData;
use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Models\Student;

class UpdateStudentContactAction
{
    public static function execute(Student $student, UpdateContactData $data, VerifyCodeData $verifyData): Student
    {
        $verified = VerifyCodeAction::execute($verifyData);

        if (! $verified || $verified->id !== $student->id) {
            return $student;
        }

        $properties = [];

        if ($data->email && $verifyData->type === 'email') {
            $properties['old_email'] = $student->email->value;
            $properties['new_email'] = $data->email->value;
            $student->email = $data->email;
            $student->markEmailAsVerified();
        }

        if ($data->phone && $verifyData->type === 'phone') {
            $properties['old_phone'] = $student->phone->value;
            $properties['new_phone'] = $data->phone->value;
            $student->phone = $data->phone;
            $student->markPhoneAsVerified();
        }

        $student->save();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentContactChanged,
            'description' => 'Student contact information updated',
            'properties' => $properties,
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $student;
    }
}
