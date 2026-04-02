<?php

namespace App\Applications\Http\Admin\Specialty\Controllers;

use Illuminate\View\View;

final class CreateSpecialtyController
{
    public function __invoke(): View
    {
        return view('admin.specialties.create');
    }
}
