<?php

namespace App\Applications\Http\Admin\Specialty\Controllers;

use App\Domains\Student\Models\Specialty;
use Illuminate\Http\RedirectResponse;

final class DeleteSpecialtyController
{
    public function __invoke(Specialty $specialty): RedirectResponse
    {
        $specialty->delete();

        return redirect()
            ->route('admin.specialties.index')
            ->with('success', 'Спеціальність успішно видалено');
    }
}
