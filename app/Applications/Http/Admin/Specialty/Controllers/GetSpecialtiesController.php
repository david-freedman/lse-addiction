<?php

namespace App\Applications\Http\Admin\Specialty\Controllers;

use App\Domains\Student\Models\Specialty;
use Illuminate\View\View;

final class GetSpecialtiesController
{
    public function __invoke(): View
    {
        $specialties = Specialty::orderBy('name')->paginate(20);

        return view('admin.specialties.index', compact('specialties'));
    }
}
