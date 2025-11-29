<?php

namespace App\Domains\User\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DeleteUserAction
{
    public static function execute(User $user): void
    {
        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::User,
            'subject_id' => $user->id,
            'activity_type' => ActivityType::UserDeleted,
            'description' => 'User deleted by admin',
            'properties' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]));

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
    }
}
