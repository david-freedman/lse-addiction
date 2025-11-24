<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\UpdateStudentData;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\Storage;

class UpdateStudentAction
{
    public static function execute(Student $student, UpdateStudentData $data): Student
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
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $attributes['profile_photo'] = $data->profile_photo->store('students/photos', 'public');
        }

        if ($data->email_verified !== null) {
            $attributes['email_verified_at'] = $data->email_verified ? now() : null;
        }

        if ($data->phone_verified !== null) {
            $attributes['phone_verified_at'] = $data->phone_verified ? now() : null;
        }

        $student->update($attributes);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentUpdatedByAdmin,
            'description' => 'Student updated by admin',
            'properties' => [
                'name' => $student->name,
                'surname' => $student->surname,
                'email' => $student->email->value,
                'phone' => $student->phone->value,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $student->fresh();
    }
}
