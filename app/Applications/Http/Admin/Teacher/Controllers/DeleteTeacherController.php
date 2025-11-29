<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use App\Domains\Teacher\Actions\DeleteTeacherAction;
use App\Domains\Teacher\Models\Teacher;
use App\Domains\Teacher\ViewModels\TeacherDetailViewModel;
use Illuminate\Http\RedirectResponse;

final class DeleteTeacherController
{
    public function __invoke(Teacher $teacher): RedirectResponse
    {
        $viewModel = new TeacherDetailViewModel($teacher);

        if (!$viewModel->canDelete()) {
            return redirect()
                ->route('admin.teachers.show', $teacher)
                ->with('error', $viewModel->deleteBlockedReason());
        }

        DeleteTeacherAction::execute($teacher);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Викладача успішно видалено');
    }
}
