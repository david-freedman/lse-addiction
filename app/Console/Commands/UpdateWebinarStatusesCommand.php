<?php

namespace App\Console\Commands;

use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class UpdateWebinarStatusesCommand extends Command
{
    protected $signature = 'webinars:update-statuses';

    protected $description = 'Update webinar statuses based on time (Upcoming → Live → Completed)';

    public function handle(): int
    {
        $now = now();

        $toLiveCount = Webinar::query()
            ->where('status', WebinarStatus::Upcoming)
            ->where('starts_at', '<=', $now)
            ->where(DB::raw('starts_at + (duration_minutes || \' minutes\')::interval'), '>', $now)
            ->update(['status' => WebinarStatus::Live]);

        $toCompletedCount = Webinar::query()
            ->whereIn('status', [WebinarStatus::Upcoming, WebinarStatus::Live])
            ->where(DB::raw('starts_at + (duration_minutes || \' minutes\')::interval'), '<=', $now)
            ->update(['status' => WebinarStatus::Completed]);

        if ($toLiveCount > 0 || $toCompletedCount > 0) {
            $this->info("Updated statuses: {$toLiveCount} to Live, {$toCompletedCount} to Completed");
        }

        return self::SUCCESS;
    }
}
