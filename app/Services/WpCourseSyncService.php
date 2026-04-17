<?php

namespace App\Services;

use App\Domains\Course\Models\Course;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WpCourseSyncService
{
    public function push(Course $course): array
    {
        $course->loadMissing(['teacher', 'tags']);

        $response = Http::withHeaders([
            'X-LSE-Secret' => config('services.lse_wp.secret'),
            'Accept'       => 'application/json',
        ])->post(config('services.lse_wp.url'), $this->buildPayload($course));

        if (! $response->successful()) {
            throw new \RuntimeException(
                "[LseSync] WP API error for course {$course->id}: HTTP {$response->status()} — {$response->body()}"
            );
        }

        return $response->json();
    }

    public function pushAll(): void
    {
        Course::with(['teacher', 'tags'])
            ->where('sync_to_wp', true)
            ->each(function (Course $course) {
                try {
                    $result = $this->push($course);
                    Log::info("[LseSync] Course {$course->id} ({$course->name}): {$result['action']} → WP post_id={$result['post_id']}");
                } catch (\Throwable $e) {
                    Log::error("[LseSync] Failed to sync course {$course->id}: {$e->getMessage()}");
                }
            });
    }

    private function buildPayload(Course $course): array
    {
        return [
            'external_id'             => $course->id,
            'name'                    => $course->name,
            'slug'                    => $course->slug,
            'number'                  => $course->number,
            'description'             => $course->description,
            'description_short'       => $course->description_short,
            'price'                   => $course->price,
            'old_price'               => $course->old_price,
            'discount_percentage'     => $course->discount_percentage,
            'status'                  => $course->status->value,
            'type'                    => $course->type?->label(),
            'label'                   => $course->label ? \App\Domains\Course\Enums\CourseLabel::tryFrom($course->label)?->label() : null,
            'starts_at'               => $course->starts_at?->toIso8601String(),
            'registration_starts_at'  => $course->registration_starts_at?->toIso8601String(),
            'registration_ends_at'    => $course->registration_ends_at?->toIso8601String(),
            'banner_url'              => $course->banner_url,
            'teacher_name'            => $course->teacher?->full_name,
            'teacher_position'        => $course->teacher?->position,
            'teacher_description'     => $course->teacher?->description,
            'teacher_avatar_url'      => $course->teacher?->avatar_url,
            'tags'                    => $course->tags->pluck('name')->all(),
        ];
    }
}
