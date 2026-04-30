<?php

namespace App\Domains\Webinar\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Quiz\Actions\DeleteQuizAction;
use App\Domains\Webinar\Data\UpdateWebinarData;
use App\Domains\Webinar\Models\Webinar;
use App\Jobs\SyncWebinarToWpJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateWebinarAction
{
    public static function execute(Webinar $webinar, UpdateWebinarData $data): Webinar
    {
        $updateData = [
            'title' => $data->title,
            'number' => $data->number,
            'description' => $data->description,
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

        if ($data->cert_signature) {
            if ($webinar->cert_signature) {
                Storage::disk('public')->delete($webinar->cert_signature);
            }
            $updateData['cert_signature'] = $data->cert_signature->store('certificates/signatures', 'public');
        }

        if ($data->cert_stamp) {
            if ($webinar->cert_stamp) {
                Storage::disk('public')->delete($webinar->cert_stamp);
            }
            $updateData['cert_stamp'] = $data->cert_stamp->store('certificates/stamps', 'public');
        }

        $updateData['cert_company_name'] = $data->cert_company_name ?? 'Медична академія';
        $updateData['cert_bpr_hours'] = $data->cert_bpr_hours;
        $updateData['cert_specialties'] = $data->cert_specialties;
        $updateData['cert_participant_type'] = $data->cert_participant_type;

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

        if ($data->sync_to_wp) {
            SyncWebinarToWpJob::dispatch($webinar);
        }

        self::handleQuizChange($webinar, $data);

        return $webinar->fresh();
    }

    private static function handleQuizChange(Webinar $webinar, UpdateWebinarData $data): void
    {
        $webinar->load('quiz');

        if (!$data->has_quiz) {
            if ($webinar->quiz) {
                DeleteQuizAction::execute($webinar->quiz);
            }
            return;
        }

        if (!$webinar->quiz) {
            $webinar->quiz()->create([
                'title' => $webinar->title,
                'passing_score' => $data->quiz_passing_score ?? 70,
                'max_attempts' => $data->quiz_max_attempts,
                'time_limit_minutes' => $data->quiz_time_limit_minutes,
                'show_correct_answers' => $data->quiz_show_correct_answers,
                'is_survey' => false,
            ]);
            return;
        }

        $quizUpdateData = [];

        if ($data->quiz_passing_score !== null) {
            $quizUpdateData['passing_score'] = $data->quiz_passing_score;
        }
        if ($data->quiz_max_attempts !== null) {
            $quizUpdateData['max_attempts'] = $data->quiz_max_attempts;
        }
        if ($data->quiz_time_limit_minutes !== null) {
            $quizUpdateData['time_limit_minutes'] = $data->quiz_time_limit_minutes;
        }
        $quizUpdateData['show_correct_answers'] = $data->quiz_show_correct_answers;

        if (!empty($quizUpdateData)) {
            $webinar->quiz->update($quizUpdateData);
        }
    }
}
