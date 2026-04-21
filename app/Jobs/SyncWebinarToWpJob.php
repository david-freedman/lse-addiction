<?php

namespace App\Jobs;

use App\Domains\Webinar\Models\Webinar;
use App\Services\WpWebinarSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncWebinarToWpJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public readonly Webinar $webinar,
    ) {}

    public function handle(WpWebinarSyncService $service): void
    {
        $result = $service->push($this->webinar);

        Log::info("[LseSync] Job: webinar {$this->webinar->id} ({$this->webinar->title}): {$result['action']} → WP post_id={$result['post_id']}");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("[LseSync] Job failed for webinar {$this->webinar->id}: {$e->getMessage()}");
    }
}
