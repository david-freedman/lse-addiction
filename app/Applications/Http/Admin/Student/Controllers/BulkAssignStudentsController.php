<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\BulkAssignStudentsAction;
use App\Domains\Student\Data\BulkAssignData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class BulkAssignStudentsController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = BulkAssignData::validateAndCreate($request->all());

        $assignedCount = BulkAssignStudentsAction::execute($data);

        return redirect()
            ->back()
            ->with('success', "Призначено {$assignedCount} студентів на курс");
    }
}
