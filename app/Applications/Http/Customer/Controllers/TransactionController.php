<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\ViewModels\TransactionHistoryViewModel;
use App\Domains\Transaction\ViewModels\TransactionStatsViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController
{
    public function index(Request $request): View
    {
        $customer = $request->user();

        $statusFilter = $request->query('status')
            ? TransactionStatus::tryFrom($request->query('status'))
            : null;

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $historyViewModel = new TransactionHistoryViewModel(
            $customer,
            $statusFilter,
            $dateFrom,
            $dateTo
        );

        $statsViewModel = new TransactionStatsViewModel($customer);

        return view('customer.transactions.index', compact('historyViewModel', 'statsViewModel'));
    }
}
