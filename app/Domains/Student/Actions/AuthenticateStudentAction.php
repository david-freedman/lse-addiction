<?php

namespace App\Domains\Student\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\Auth;

class AuthenticateStudentAction
{
    public static function execute(Student $student): void
    {
        $student->update(['last_login_at' => now()]);

        Auth::guard('web')->login($student);
        request()->session()->regenerate();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => $student->id,
            'activity_type' => ActivityType::StudentLoginSuccess,
            'description' => 'Student logged in successfully',
            'properties' => [
                'email' => $student->email->value,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));
    }
}
