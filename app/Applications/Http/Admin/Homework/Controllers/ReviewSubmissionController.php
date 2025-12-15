<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Homework\Actions\ReviewSubmissionAction;
use App\Domains\Homework\Data\ReviewHomeworkData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\HomeworkSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

final class ReviewSubmissionController
{
    public function __invoke(
        Request $request,
        HomeworkSubmission $submission
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', new Enum(HomeworkSubmissionStatus::class)],
            'score' => ['nullable', 'integer', 'min:0', 'max:' . $submission->homework->max_points],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ]);

        $data = new ReviewHomeworkData(
            status: HomeworkSubmissionStatus::from($validated['status']),
            score: $validated['score'] ?? null,
            feedback: $validated['feedback'] ?? null,
        );

        app(ReviewSubmissionAction::class)($submission, $data, Auth::user());

        return redirect()
            ->route('admin.homework.index')
            ->with('success', 'Роботу успішно перевірено!');
    }
}
