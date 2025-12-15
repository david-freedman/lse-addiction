<?php

namespace App\Domains\Homework\Actions;

use App\Domains\Homework\Data\ReviewHomeworkData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Models\User;
use App\Notifications\HomeworkReviewedNotification;

final class ReviewSubmissionAction
{
    public function __invoke(HomeworkSubmission $submission, ReviewHomeworkData $data, User $reviewer): HomeworkSubmission
    {
        $submission->update([
            'status' => $data->status,
            'score' => $data->score,
            'feedback' => $data->feedback,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        $submission->student->notify(new HomeworkReviewedNotification($submission));

        return $submission->fresh();
    }
}
