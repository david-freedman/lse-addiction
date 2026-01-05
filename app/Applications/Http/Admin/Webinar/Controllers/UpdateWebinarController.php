<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Webinar\Actions\UpdateWebinarAction;
use App\Domains\Webinar\Data\UpdateWebinarData;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateWebinarController
{
    public function __invoke(Request $request, Webinar $webinar): RedirectResponse
    {
        $data = UpdateWebinarData::validateAndCreate($request->all());

        UpdateWebinarAction::execute($webinar, $data);

        return redirect()
            ->route('admin.webinars.edit', $webinar)
            ->with('success', 'Вебінар успішно оновлено');
    }
}
