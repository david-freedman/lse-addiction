<?php

namespace App\Domains\Teacher\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Teacher\Data\UpdateTeacherData;
use App\Domains\Teacher\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateTeacherAction
{
    public static function execute(Teacher $teacher, UpdateTeacherData $data): Teacher
    {
        return DB::transaction(function () use ($teacher, $data) {
            $user = $teacher->user;

            $userAttributes = [
                'name' => $data->last_name.' '.$data->first_name,
                'email' => $data->email,
            ];

            if ($data->photo) {
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }
                $userAttributes['photo'] = $data->photo->store('users/photos', 'public');
            }

            $user->update($userAttributes);

            $teacher->update([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'middle_name' => $data->middle_name,
                'position' => $data->position,
                'workplace' => $data->workplace,
                'specialization' => $data->specialization,
                'description' => $data->description,
            ]);

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Teacher,
                'subject_id' => $teacher->id,
                'activity_type' => ActivityType::TeacherUpdated,
                'description' => 'Teacher updated by admin',
                'properties' => [
                    'name' => $teacher->full_name,
                    'email' => $user->email,
                ],
            ]));

            return $teacher->fresh();
        });
    }
}
