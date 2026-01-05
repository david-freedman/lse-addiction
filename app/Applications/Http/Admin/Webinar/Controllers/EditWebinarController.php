<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Teacher\Models\Teacher;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\View\View;

final class EditWebinarController
{
    public function __invoke(Webinar $webinar): View
    {
        $webinar->load(['teacher']);

        $teachers = Teacher::orderBy('last_name')->get();
        $statuses = WebinarStatus::cases();

        return view('admin.webinars.edit', compact('webinar', 'teachers', 'statuses'));
    }
}
