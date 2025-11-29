<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LogoutController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $studentId = Auth::id();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($studentId) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => $studentId,
                'activity_type' => ActivityType::StudentLoggedOut,
                'description' => 'Student logged out',
                'properties' => [
                    'reason' => 'user_initiated',
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return redirect()->route('student.login');
    }
}
