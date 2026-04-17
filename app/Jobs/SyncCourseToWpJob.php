<?php

namespace App\Jobs;

use App\Domains\Course\Models\Course;
use App\Services\WpCourseSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncCourseToWpJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public readonly Course $course,
    ) {}

    public function handle(WpCourseSyncService $service): void
    {
        $result = $service->push($this->course);

        Log::info("[LseSync] Job: course {$this->course->id} ({$this->course->name}): {$result['action']} → WP post_id={$result['post_id']}");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("[LseSync] Job failed for course {$this->course->id}: {$e->getMessage()}");
    }
}
