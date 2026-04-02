<?php

namespace App\Applications\Http\Admin\Specialty\Controllers;

use App\Domains\Student\Actions\CreateSpecialtyAction;
use App\Domains\Student\Data\CreateSpecialtyData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreSpecialtyController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateSpecialtyData::validateAndCreate($request->all());

        CreateSpecialtyAction::execute($data);

        return redirect()
            ->route('admin.specialties.index')
            ->with('success', 'Спеціальність успішно створено');
    }
}
