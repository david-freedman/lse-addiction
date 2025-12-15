<?php

namespace App\Applications\Http\Admin\Webinar\Controllers;

use App\Domains\Webinar\Data\WebinarFilterData;
use App\Domains\Webinar\ViewModels\Admin\WebinarListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetWebinarsController
{
    public function __invoke(Request $request): View
    {
        $filters = WebinarFilterData::from($request->all());
        $viewModel = new WebinarListViewModel(
            filters: $filters,
            perPage: 20,
        );

        return view('admin.webinars.index', compact('viewModel'));
    }
}
