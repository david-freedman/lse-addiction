<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\Storage;

class DeleteStudentSelfAction
{
    public static function execute(Student $student): bool
    {
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
            $student->profile_photo = null;
            $student->save();
        }

        $student->verifications()->delete();

        $deleted = $student->delete();

        if ($deleted) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => $student->id,
                'activity_type' => ActivityType::StudentDeletedSelf,
                'description' => 'Student deleted their own account',
                'properties' => [
                    'name' => $student->name,
                    'surname' => $student->surname,
                    'email' => $student->email->value,
                    'phone' => $student->phone->value,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]));
        }

        return $deleted;
    }
}
