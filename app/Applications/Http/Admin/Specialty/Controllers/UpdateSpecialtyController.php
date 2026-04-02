<?php

namespace App\Applications\Http\Admin\Specialty\Controllers;

use App\Domains\Student\Actions\UpdateSpecialtyAction;
use App\Domains\Student\Data\UpdateSpecialtyData;
use App\Domains\Student\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateSpecialtyController
{
    public function __invoke(Request $request, Specialty $specialty): RedirectResponse
    {
        $data = UpdateSpecialtyData::validateAndCreate($request->all());

        UpdateSpecialtyAction::execute($specialty, $data);

        return redirect()
            ->route('admin.specialties.index')
            ->with('success', 'Спеціальність успішно оновлено');
    }
}
