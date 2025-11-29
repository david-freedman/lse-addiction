<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use App\Domains\Teacher\Actions\CreateTeacherAction;
use App\Domains\Teacher\Data\CreateTeacherData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreTeacherController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateTeacherData::validateAndCreate($request->all());

        $teacher = CreateTeacherAction::execute($data);

        return redirect()
            ->route('admin.teachers.show', $teacher)
            ->with('success', 'Викладача успішно створено');
    }
}
