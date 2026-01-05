<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Webinar\Actions\CreateWebinarAction;
use App\Domains\Webinar\Data\CreateWebinarData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreWebinarController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateWebinarData::validateAndCreate($request->all());

        $webinar = CreateWebinarAction::execute($data);

        return redirect()
            ->route('admin.webinars.edit', $webinar)
            ->with('success', 'Вебінар успішно створено');
    }
}
