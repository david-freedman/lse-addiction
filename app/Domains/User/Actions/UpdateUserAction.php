<?php

namespace App\Domains\User\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\User\Data\UpdateUserData;
use App\Models\User;

class UpdateUserAction
{
    public static function execute(User $user, UpdateUserData $data): User
    {
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
        ];

        $attributes = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
        ];

        if ($data->is_active !== null) {
            $attributes['is_active'] = $data->is_active;
        }

        if ($data->photo) {
            $attributes['photo'] = $data->photo->store('users/photos', 'public');
        }

        $user->update($attributes);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::User,
            'subject_id' => $user->id,
            'activity_type' => ActivityType::UserUpdated,
            'description' => 'User updated by admin',
            'properties' => [
                'old' => $oldValues,
                'new' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                ],
            ],
        ]));

        return $user;
    }
}
