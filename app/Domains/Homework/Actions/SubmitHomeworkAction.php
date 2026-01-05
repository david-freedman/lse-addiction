<?php

namespace App\Domains\Homework\Actions;

use App\Domains\Homework\Data\SubmitHomeworkData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\Homework;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\Storage;

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

        return HomeworkSubmission::create([
            'homework_id' => $homework->id,
            'student_id' => $student->id,
            'attempt_number' => $attemptNumber,
            'text_response' => $data->text_response,
            'files' => !empty($filePaths) ? $filePaths : null,
            'status' => HomeworkSubmissionStatus::Pending,
            'is_late' => $isLate,
            'submitted_at' => now(),
        ]);
    }
}
