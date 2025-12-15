<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Teacher\Models\Teacher;
use App\Domains\Webinar\Enums\WebinarStatus;
use Illuminate\View\View;

final class CreateWebinarController
{
    public function __invoke(): View
    {
        $teachers = Teacher::orderBy('last_name')->get();
        $statuses = WebinarStatus::cases();

        return view('admin.webinars.create', compact('teachers', 'statuses'));
    }
}
