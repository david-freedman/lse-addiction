<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Homework\ViewModels\Admin\SubmissionDetailViewModel;
use Illuminate\View\View;

final class ShowSubmissionController
{
    public function __invoke(HomeworkSubmission $submission): View
    {
        $viewModel = new SubmissionDetailViewModel(
            submission: $submission->load(['student', 'homework.lesson.module.course', 'reviewer']),
        );

        return view('admin.homework.submissions.show', compact('viewModel'));
    }
}
