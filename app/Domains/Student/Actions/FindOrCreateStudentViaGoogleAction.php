<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\Student;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class FindOrCreateStudentViaGoogleAction
{
    public static function execute(SocialiteUser $googleUser): Student
    {
        $student = Student::where('google_id', $googleUser->getId())->first();

        if ($student) {
            return $student;
        }

        $student = Student::where('email', $googleUser->getEmail())->first();

        if ($student) {
            $student->update(['google_id' => $googleUser->getId()]);

            return $student;
        }

        $nameParts = explode(' ', trim($googleUser->getName() ?? ''), 2);
        $name = $nameParts[0] ?? null;
        $surname = $nameParts[1] ?? null;

        $student = Student::create([
            'number'           => Student::generateNumber(),
            'email'            => $googleUser->getEmail(),
            'name'             => $name,
            'surname'          => $surname,
            'google_id'        => $googleUser->getId(),
            'email_verified_at' => now(),
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id'   => $student->id,
            'activity_type' => ActivityType::StudentRegistered,
            'description'  => 'Student registered via Google OAuth',
            'properties'   => ['email' => $student->email->value],
            'ip_address'   => null,
            'user_agent'   => null,
        ]));

        return $student;
    }
}
