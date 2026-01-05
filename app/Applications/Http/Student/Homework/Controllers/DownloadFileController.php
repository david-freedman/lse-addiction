<?php

namespace App\Applications\Http\Student\Homework\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DownloadFileController
{
    public function __invoke(Course $course, Lesson $lesson, HomeworkSubmission $submission, int $index): StreamedResponse
    {
        $student = Auth::user();

        if ($submission->student_id !== $student->id) {
            abort(403);
        }

        if (!$submission->files || !isset($submission->files[$index])) {
            abort(404);
        }

        $filePath = $submission->files[$index];

        if (!Storage::disk('private')->exists($filePath)) {
            abort(404);
        }

        $fileName = basename($filePath);

        return Storage::disk('private')->download($filePath, $fileName);
    }
}
