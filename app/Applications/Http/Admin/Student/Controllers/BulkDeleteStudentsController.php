<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\BulkDeleteStudentsAction;
use App\Domains\Student\Data\BulkDeleteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class BulkDeleteStudentsController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = BulkDeleteData::validateAndCreate($request->all());

        $deletedCount = BulkDeleteStudentsAction::execute($data);

        return redirect()
            ->back()
            ->with('success', "Видалено {$deletedCount} студентів");
    }
}
