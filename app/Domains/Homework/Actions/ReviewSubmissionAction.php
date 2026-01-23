<?php

namespace App\Domains\Homework\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Homework\Data\ReviewHomeworkData;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Models\User;
use App\Notifications\HomeworkReviewedNotification;

final class ReviewSubmissionAction
{
    public function __invoke(HomeworkSubmission $submission, ReviewHomeworkData $data, User $reviewer): HomeworkSubmission
    {
        $previousStatus = $submission->status;

        $submission->update([
            'status' => $data->status,
            'score' => $data->score,
            'feedback' => $data->feedback,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        $submission->student->notify(new HomeworkReviewedNotification($submission));

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::HomeworkSubmission,
            'subject_id' => $submission->id,
            'activity_type' => ActivityType::HomeworkReviewed,
            'description' => 'Homework reviewed',
            'properties' => [
                'homework_id' => $submission->homework_id,
                'student_id' => $submission->student_id,
                'reviewer_id' => $reviewer->id,
                'reviewer_email' => $reviewer->email,
                'previous_status' => $previousStatus->value,
                'new_status' => $data->status->value,
                'score' => $data->score,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $submission->fresh();
    }
}
