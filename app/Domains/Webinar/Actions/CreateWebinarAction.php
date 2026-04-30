<?php

namespace App\Domains\Webinar\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Webinar\Data\CreateWebinarData;
use App\Domains\Webinar\Models\Webinar;
use App\Jobs\SyncWebinarToWpJob;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateWebinarAction
{
    public static function execute(CreateWebinarData $data): Webinar
    {
        $bannerPath = null;
        if ($data->banner) {
            $bannerPath = $data->banner->store('webinars', 'public');
        }

        $signaturePath = null;
        if ($data->cert_signature) {
            $signaturePath = $data->cert_signature->store('certificates/signatures', 'public');
        }

        $stampPath = null;
        if ($data->cert_stamp) {
            $stampPath = $data->cert_stamp->store('certificates/stamps', 'public');
        }

        $slug = $data->slug ?: Str::slug($data->title);
        $originalSlug = $slug;
        $counter = 1;
        while (Webinar::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $webinar = Webinar::create([
            'title' => $data->title,
            'slug' => $slug,
            'number' => $data->number,
            'description' => $data->description,
            'banner' => $bannerPath,
            'teacher_id' => $data->teacher_id,
            'starts_at' => $data->starts_at ? Carbon::createFromFormat('d.m.Y H:i', $data->starts_at) : null,
            'duration_minutes' => $data->duration_minutes,
            'meeting_url' => $data->meeting_url,
            'recording_url' => $data->recording_url,
            'status' => $data->status,
            'max_participants' => $data->max_participants,
            'price' => $data->price,
            'old_price' => $data->old_price,
            'sync_to_wp' => $data->sync_to_wp,
            'cert_company_name' => $data->cert_company_name ?? 'Медична академія',
            'cert_signature' => $signaturePath,
            'cert_stamp' => $stampPath,
            'cert_bpr_hours' => $data->cert_bpr_hours,
            'cert_specialties' => $data->cert_specialties,
            'cert_participant_type' => $data->cert_participant_type,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Webinar,
            'subject_id' => $webinar->id,
            'activity_type' => ActivityType::WebinarCreated,
            'description' => 'Webinar created',
            'properties' => [
                'title' => $data->title,
                'status' => $data->status,
                'price' => $data->price,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        if ($data->sync_to_wp) {
            SyncWebinarToWpJob::dispatch($webinar);
        }

        return $webinar;
    }
}
