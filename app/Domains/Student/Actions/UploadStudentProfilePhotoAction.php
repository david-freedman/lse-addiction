<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadStudentProfilePhotoAction
{
    public static function execute(Student $student, UploadedFile $photo): Student
    {
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }

        $path = $photo->store('profile-photos', 'public');

        $student->update([
            'profile_photo' => $path,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentPersonalDetailsUpdated,
            'description' => 'Student profile photo updated',
            'properties' => [
                'photo_path' => $path,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $student->fresh();
    }

    public static function delete(Student $student): Student
    {
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);

            $student->update([
                'profile_photo' => null,
            ]);

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => $student->id,
                'activity_type' => ActivityType::StudentPersonalDetailsUpdated,
                'description' => 'Student profile photo deleted',
                'properties' => [],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return $student->fresh();
    }
}
