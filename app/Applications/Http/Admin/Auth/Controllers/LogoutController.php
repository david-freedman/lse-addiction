<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

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
        $user = Auth::guard('admin')->user();

        if ($user) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Admin,
                'subject_id' => $user->id,
                'activity_type' => ActivityType::AdminLoggedOut,
                'description' => 'Admin logged out',
                'properties' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]));
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
