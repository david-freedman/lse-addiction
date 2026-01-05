<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\IssueCertificateAction;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

final class IssueCertificateController
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('issue', Certificate::class);

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $course = Course::findOrFail($request->course_id);

        $certificate = app(IssueCertificateAction::class)(
            $student,
            $course,
            auth()->id(),
            $request->filled('grade') ? (float) $request->grade : null
        );

        if ($certificate) {
            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Сертифікат успішно видано');
        }

        return redirect()->route('admin.students.show', $student)
            ->with('error', 'Сертифікат для цього курсу вже існує');
    }
}
