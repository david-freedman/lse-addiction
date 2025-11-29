<?php

namespace App\Domains\Teacher\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Teacher\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteTeacherAction
{
    public static function execute(Teacher $teacher): bool
    {
        return DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacherName = $teacher->full_name;
            $teacherEmail = $user->email;
            $teacherId = $teacher->id;

            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->delete();

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Teacher,
                'subject_id' => $teacherId,
                'activity_type' => ActivityType::TeacherDeleted,
                'description' => 'Teacher deleted by admin',
                'properties' => [
                    'name' => $teacherName,
                    'email' => $teacherEmail,
                ],
            ]));

            return true;
        });
    }
}
