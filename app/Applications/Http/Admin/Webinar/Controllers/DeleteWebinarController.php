<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Webinar\Actions\DeleteWebinarAction;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;

final class DeleteWebinarController
{
    public function __invoke(Webinar $webinar): RedirectResponse
    {
        DeleteWebinarAction::execute($webinar);

        return redirect()
            ->route('admin.webinars.index')
            ->with('success', 'Вебінар успішно видалено');
    }
}
