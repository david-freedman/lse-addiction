<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Models\Customer;
use App\Domains\Customer\Models\CustomerProfileFieldValue;
use App\Domains\Customer\Models\ProfileField;

class SaveCustomerProfileFieldValuesAction
{
    public static function execute(Customer $customer, array $fieldValues): void
    {
        $activeFields = ProfileField::active()->get()->keyBy('key');

        foreach ($fieldValues as $fieldKey => $value) {
            if (!$activeFields->has($fieldKey)) {
                continue;
            }

            $field = $activeFields->get($fieldKey);

            if ($field->is_required && empty($value)) {
                continue;
            }

            CustomerProfileFieldValue::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'profile_field_id' => $field->id,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::ProfileFieldsCompleted,
            'description' => 'Customer completed profile fields',
            'properties' => [
                'fields_count' => count($fieldValues),
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));
    }
}
