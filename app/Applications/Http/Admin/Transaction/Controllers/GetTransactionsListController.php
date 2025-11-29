<?php

namespace App\Applications\Http\Admin\Transaction\Controllers;

use App\Domains\Transaction\Data\TransactionFilterData;
use App\Domains\Transaction\ViewModels\TransactionListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetTransactionsListController
{
    public function __invoke(Request $request): View
    {
        $filters = TransactionFilterData::validateAndCreate($request->all());
        $listViewModel = new TransactionListViewModel($filters);

        return view('admin.finances.transactions', compact('listViewModel'));
    }
}
