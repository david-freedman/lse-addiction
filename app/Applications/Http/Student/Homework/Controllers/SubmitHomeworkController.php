<?php

namespace App\Applications\Http\Student\Homework\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Actions\SubmitHomeworkAction;
use App\Domains\Homework\Data\SubmitHomeworkData;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class SubmitHomeworkController
{
    public function __invoke(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $homework = $lesson->homework;

        if (!$homework) {
            abort(404);
        }

        $student = Auth::user();

        if (!$homework->canStudentSubmit($student->id)) {
            throw ValidationException::withMessages([
                'submission' => 'Ви не можете здати це завдання.',
            ]);
        }

        $rules = [];

        if ($homework->response_type->allowsText()) {
            $rules['text_response'] = ['nullable', 'string', 'max:50000'];
        }

        if ($homework->response_type->allowsFiles()) {
            $rules['files'] = ['nullable', 'array', 'max:' . $homework->max_files];
            $rules['files.*'] = [
                'file',
                'max:' . ($homework->max_file_size_mb * 1024),
            ];

            if ($homework->allowed_extensions) {
                $rules['files.*'][] = 'mimes:' . implode(',', $homework->allowed_extensions);
            }
        }

        $validated = $request->validate($rules);

        if (empty($validated['text_response']) && empty($validated['files'])) {
            throw ValidationException::withMessages([
                'submission' => 'Додайте текст або файли для здачі.',
            ]);
        }

        $data = new SubmitHomeworkData(
            text_response: $validated['text_response'] ?? null,
            files: $validated['files'] ?? null,
        );

        app(SubmitHomeworkAction::class)($homework, $student, $data);

        return redirect()
            ->back()
            ->with('success', 'Домашнє завдання успішно здано!');
    }
}
