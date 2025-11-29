<?php

namespace App\Applications\Http\Admin\Transaction\Controllers;

use App\Domains\Transaction\Data\TransactionFilterData;
use App\Domains\Transaction\ViewModels\AdminTransactionStatsViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetFinancesController
{
    public function __invoke(Request $request): View
    {
        $filters = TransactionFilterData::validateAndCreate($request->all());
        $period = $request->get('period', 'all');

        $statsViewModel = new AdminTransactionStatsViewModel($filters, $period);

        return view('admin.finances.index', compact('statsViewModel'));
    }
}
