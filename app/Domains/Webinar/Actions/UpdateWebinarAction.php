<?php

namespace App\Domains\Webinar\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Webinar\Data\UpdateWebinarData;
use App\Domains\Webinar\Models\Webinar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateWebinarAction
{
    public static function execute(Webinar $webinar, UpdateWebinarData $data): Webinar
    {
        $updateData = [
            'title' => $data->title,
            'description' => $data->description,
            'teacher_id' => $data->teacher_id,
            'starts_at' => Carbon::createFromFormat('d.m.Y H:i', $data->starts_at),
            'duration_minutes' => $data->duration_minutes,
            'meeting_url' => $data->meeting_url,
            'status' => $data->status,
            'max_participants' => $data->max_participants,
            'price' => $data->price,
            'old_price' => $data->old_price,
        ];

        if ($data->slug && $data->slug !== $webinar->slug) {
            $slug = Str::slug($data->slug);
            $originalSlug = $slug;
            $counter = 1;
            while (Webinar::where('slug', $slug)->where('id', '!=', $webinar->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $updateData['slug'] = $slug;
        }

        if ($data->banner) {
            if ($webinar->banner) {
                Storage::disk('public')->delete($webinar->banner);
            }
            $updateData['banner'] = $data->banner->store('webinars', 'public');
        }

        $webinar->update($updateData);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Webinar,
            'subject_id' => $webinar->id,
            'activity_type' => ActivityType::WebinarUpdated,
            'description' => 'Webinar updated',
            'properties' => [
                'title' => $data->title,
                'status' => $data->status,
                'price' => $data->price,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $webinar->fresh();
    }
}
