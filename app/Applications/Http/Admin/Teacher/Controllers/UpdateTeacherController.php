<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use App\Domains\Teacher\Actions\UpdateTeacherAction;
use App\Domains\Teacher\Data\UpdateTeacherData;
use App\Domains\Teacher\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class UpdateTeacherController
{
    public function __invoke(Request $request, Teacher $teacher): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->user_id)],
        ]);

        $data = UpdateTeacherData::validateAndCreate($request->all());

        UpdateTeacherAction::execute($teacher, $data);

        return redirect()
            ->route('admin.teachers.show', $teacher)
            ->with('success', 'Дані викладача успішно оновлено');
    }
}
