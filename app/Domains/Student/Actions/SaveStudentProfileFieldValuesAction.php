<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Enums\ProfileFieldType;
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

            if ($field->type === ProfileFieldType::Tags) {
                $value = self::sanitizeTags($value, $field->options['max_items'] ?? 5);
            }

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

    private static function sanitizeTags(mixed $value, int $maxItems): ?string
    {
        if (! is_array($value)) {
            return null;
        }

        $tags = collect($value)
            ->map(fn (string $tag) => trim($tag))
            ->filter(fn (string $tag) => mb_strlen($tag) >= 2 && mb_strlen($tag) <= 50)
            ->uniqueStrict()
            ->take($maxItems)
            ->values()
            ->all();

        if (empty($tags)) {
            return null;
        }

        return json_encode($tags, JSON_UNESCAPED_UNICODE);
    }
}
