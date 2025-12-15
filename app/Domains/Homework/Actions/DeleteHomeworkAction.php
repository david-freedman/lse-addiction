<?php

namespace App\Domains\Homework\Actions;

use App\Domains\Homework\Models\Homework;
use Illuminate\Support\Facades\Storage;

final class DeleteHomeworkAction
{
    public function __invoke(Homework $homework): void
    {
        foreach ($homework->submissions as $submission) {
            if ($submission->files) {
                foreach ($submission->files as $file) {
                    Storage::disk('private')->delete($file);
                }
            }
        }

        $homework->delete();
    }
}
