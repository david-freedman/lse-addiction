<?php

namespace App\Domains\Homework\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Homework\Data\SubmitHomeworkData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\Homework;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Student\Models\Student;

final class SubmitHomeworkAction
{
    public function __invoke(Homework $homework, Student $student, SubmitHomeworkData $data): HomeworkSubmission
    {
        $attemptNumber = $homework->submissions()
            ->where('student_id', $student->id)
            ->max('attempt_number') ?? 0;

        $attemptNumber++;

        $isLate = $homework->deadline_at && now()->gt($homework->deadline_at);

        $filePaths = [];
        if ($data->files) {
            foreach ($data->files as $file) {
                $filePaths[] = $file->store(
                    "homework/{$homework->id}/{$student->id}",
                    'private'
                );
            }
        }

        $submission = HomeworkSubmission::create([
            'homework_id' => $homework->id,
            'student_id' => $student->id,
            'attempt_number' => $attemptNumber,
            'text_response' => $data->text_response,
            'files' => !empty($filePaths) ? $filePaths : null,
            'status' => HomeworkSubmissionStatus::Pending,
            'is_late' => $isLate,
            'submitted_at' => now(),
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::HomeworkSubmission,
            'subject_id' => $submission->id,
            'activity_type' => ActivityType::HomeworkSubmitted,
            'description' => 'Homework submitted',
            'properties' => [
                'homework_id' => $homework->id,
                'homework_title' => $homework->title,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
                'attempt_number' => $attemptNumber,
                'is_late' => $isLate,
                'has_files' => !empty($filePaths),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $submission;
    }
}
