<?php

namespace App\Livewire\Admin;

use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\ActivityLog\Models\ActivityLog;
use Illuminate\Contracts\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Component;
use Livewire\WithPagination;

final class StudentLoginHistory extends Component
{
    use WithPagination;

    public int $studentId;

    public function mount(int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function render(): View
    {
        $loginHistory = ActivityLog::where('subject_type', ActivitySubject::Student)
            ->where('subject_id', $this->studentId)
            ->whereIn('activity_type', [
                ActivityType::StudentLoginSuccess,
                ActivityType::StudentLoginFailed,
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('livewire.admin.student-login-history', [
            'loginHistory' => $loginHistory,
        ]);
    }

    public function parseUserAgent(?string $userAgent): array
    {
        if (!$userAgent) {
            return ['os' => '-', 'browser' => '-', 'device' => '-'];
        }

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $device = match (true) {
            $agent->isDesktop() => 'Декстоп',
            $agent->isMobile() => 'Смартфон',
            $agent->isTablet() => 'Планшет',
            default => 'Невідомо',
        };

        return [
            'os' => $agent->platform() ?: 'Невідомо',
            'browser' => $agent->browser() ?: 'Невідомо',
            'device' => $device,
        ];
    }
}
