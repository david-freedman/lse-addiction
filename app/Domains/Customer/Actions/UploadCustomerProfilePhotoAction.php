<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Models\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadCustomerProfilePhotoAction
{
    public static function execute(Customer $customer, UploadedFile $photo): Customer
    {
        if ($customer->profile_photo) {
            Storage::disk('public')->delete($customer->profile_photo);
        }

        $path = $photo->store('profile-photos', 'public');

        $customer->update([
            'profile_photo' => $path,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::CustomerPersonalDetailsUpdated,
            'description' => 'Customer profile photo updated',
            'properties' => [
                'photo_path' => $path,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $customer->fresh();
    }

    public static function delete(Customer $customer): Customer
    {
        if ($customer->profile_photo) {
            Storage::disk('public')->delete($customer->profile_photo);

            $customer->update([
                'profile_photo' => null,
            ]);

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Customer,
                'subject_id' => $customer->id,
                'activity_type' => ActivityType::CustomerPersonalDetailsUpdated,
                'description' => 'Customer profile photo deleted',
                'properties' => [],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return $customer->fresh();
    }
}
