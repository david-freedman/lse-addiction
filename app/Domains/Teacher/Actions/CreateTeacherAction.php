<?php

namespace App\Domains\Teacher\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Teacher\Data\CreateTeacherData;
use App\Domains\Teacher\Models\Teacher;
use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateTeacherAction
{
    public static function execute(CreateTeacherData $data): Teacher
    {
        return DB::transaction(function () use ($data) {
            $photoPath = $data->photo->store('users/photos', 'public');

            $user = User::create([
                'name' => $data->last_name.' '.$data->first_name,
                'email' => $data->email,
                'role' => UserRole::Teacher,
                'photo' => $photoPath,
                'is_active' => true,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
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
                'activity_type' => ActivityType::TeacherCreated,
                'description' => 'Teacher created by admin',
                'properties' => [
                    'name' => $teacher->full_name,
                    'email' => $user->email,
                    'specialization' => $teacher->specialization,
                ],
            ]));

            return $teacher;
        });
    }
}
