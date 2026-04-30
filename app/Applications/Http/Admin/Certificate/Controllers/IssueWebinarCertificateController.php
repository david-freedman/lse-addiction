<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\IssueWebinarCertificateAction;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class IssueWebinarCertificateController
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('issue', Certificate::class);

        $request->validate([
            'webinar_id' => 'required|exists:webinars,id',
            'grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $webinar = Webinar::findOrFail($request->webinar_id);

        $certificate = app(IssueWebinarCertificateAction::class)(
            $student,
            $webinar,
            auth()->id(),
            $request->filled('grade') ? (float) $request->grade : null
        );

        if ($certificate) {
            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Сертифікат вебінару успішно видано');
        }

        return redirect()->route('admin.students.show', $student)
            ->with('error', 'Сертифікат для цього вебінару вже існує');
    }
}
