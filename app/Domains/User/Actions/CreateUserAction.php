<?php

namespace App\Domains\User\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\User\Data\CreateUserData;
use App\Models\User;

class CreateUserAction
{
    public static function execute(CreateUserData $data): User
    {
        $attributes = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
            'is_active' => $data->is_active ?? true,
        ];

        if ($data->photo) {
            $attributes['photo'] = $data->photo->store('users/photos', 'public');
        }

        $user = User::create($attributes);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::User,
            'subject_id' => $user->id,
            'activity_type' => ActivityType::UserCreated,
            'description' => 'User created by admin',
            'properties' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]));

        return $user;
    }
}
