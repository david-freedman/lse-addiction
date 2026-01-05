<?php

namespace App\Domains\Homework\Observers;

use App\Domains\Homework\Models\HomeworkSubmission;
use Illuminate\Support\Facades\Storage;

class HomeworkSubmissionObserver
{
    public function deleting(HomeworkSubmission $submission): void
    {
        if ($submission->files) {
            foreach ($submission->files as $file) {
                Storage::disk('private')->delete($file);
            }
        }
    }
}
