<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Homework\Models\HomeworkSubmission;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DownloadSubmissionFileController
{
    public function __invoke(HomeworkSubmission $submission, int $index): StreamedResponse
    {
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
