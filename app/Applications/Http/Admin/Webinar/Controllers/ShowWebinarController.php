<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Webinar\Models\Webinar;
use App\Domains\Webinar\ViewModels\Admin\WebinarDetailViewModel;
use Illuminate\View\View;

final class ShowWebinarController
{
    public function __invoke(Webinar $webinar): View
    {
        $viewModel = new WebinarDetailViewModel($webinar);

        return view('admin.webinars.show', [
            'viewModel' => $viewModel,
            'breadcrumbs' => [
                ['title' => 'Вебінари', 'url' => route('admin.webinars.index')],
                ['title' => $webinar->title],
            ],
        ]);
    }
}
