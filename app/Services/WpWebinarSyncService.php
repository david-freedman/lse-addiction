<?php

namespace App\Services;

use App\Domains\Webinar\Models\Webinar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WpWebinarSyncService
{
    public function push(Webinar $webinar): array
    {
        $webinar->loadMissing(['teachers']);

        $response = Http::withHeaders([
            'X-LSE-Secret' => config('services.lse_wp.secret'),
            'Accept'       => 'application/json',
        ])->post(config('services.lse_wp.url'), $this->buildPayload($webinar));

        if (! $response->successful()) {
            throw new \RuntimeException(
                "[LseSync] WP API error for webinar {$webinar->id}: HTTP {$response->status()} — {$response->body()}"
            );
        }

        return $response->json();
    }

    public function pushAll(): void
    {
        Webinar::with(['teachers'])
            ->where('sync_to_wp', true)
            ->each(function (Webinar $webinar) {
                try {
                    $result = $this->push($webinar);
                    Log::info("[LseSync] Webinar {$webinar->id} ({$webinar->title}): {$result['action']} → WP post_id={$result['post_id']}");
                } catch (\Throwable $e) {
                    Log::error("[LseSync] Failed to sync webinar {$webinar->id}: {$e->getMessage()}");
                }
            });
    }

    private function buildPayload(Webinar $webinar): array
    {
        return [
            'resource_type'       => 'webinar',
            'external_id'         => 'w' . $webinar->id,
            'name'                => $webinar->title,
            'slug'                => $webinar->slug,
            'description'         => $webinar->description,
            'price'               => $webinar->price,
            'old_price'           => $webinar->old_price,
            'status'              => $webinar->status->value,
            'starts_at'           => $webinar->starts_at?->toIso8601String(),
            'duration_minutes'    => $webinar->duration_minutes,
            'banner_url'          => $webinar->banner_url,
            'teachers'            => $webinar->teachers->map(fn ($t) => [
                'name'        => $t->full_name,
                'position'    => $t->position,
                'description' => $t->description,
                'avatar_url'  => $t->avatar_url,
            ])->all(),
        ];
    }
}
