<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\ProfileField;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentProfileFieldValue;

class SaveStudentProfileFieldValuesAction
{
    public static function execute(Student $student, array $fieldValues): void
    {
        $activeFields = ProfileField::active()->get()->keyBy('key');

        foreach ($fieldValues as $fieldKey => $value) {
            if (! $activeFields->has($fieldKey)) {
                continue;
            }

            $field = $activeFields->get($fieldKey);

            if ($field->is_required && empty($value)) {
                continue;
            }

            StudentProfileFieldValue::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'profile_field_id' => $field->id,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::ProfileFieldsCompleted,
            'description' => 'Student completed profile fields',
            'properties' => [
                'fields_count' => count($fieldValues),
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));
    }
}
