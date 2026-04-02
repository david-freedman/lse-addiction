<?php

namespace App\Applications\Http\Admin\Specialty\Controllers;

use App\Domains\Student\Models\Specialty;
use Illuminate\View\View;

final class EditSpecialtyController
{
    public function __invoke(Specialty $specialty): View
    {
        return view('admin.specialties.edit', compact('specialty'));
    }
}
