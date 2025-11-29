<?php

namespace App\Domains\User\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Models\User;

class ToggleUserStatusAction
{
    public static function execute(User $user): User
    {
        $oldStatus = $user->is_active;
        $newStatus = !$oldStatus;

        $user->update(['is_active' => $newStatus]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::User,
            'subject_id' => $user->id,
            'activity_type' => ActivityType::UserStatusChanged,
            'description' => $newStatus ? 'User activated by admin' : 'User deactivated by admin',
            'properties' => [
                'name' => $user->name,
                'email' => $user->email,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]));

        return $user;
    }
}
