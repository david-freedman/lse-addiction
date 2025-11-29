<?php

namespace App\Applications\Http\Student\Transaction\Controllers;

use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\ViewModels\TransactionHistoryViewModel;
use App\Domains\Transaction\ViewModels\TransactionStatsViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetTransactionsController
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();

        $statusFilter = $request->query('status')
            ? TransactionStatus::tryFrom($request->query('status'))
            : null;

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $historyViewModel = new TransactionHistoryViewModel(
            $student,
            $statusFilter,
            $dateFrom,
            $dateTo
        );

        $statsViewModel = new TransactionStatsViewModel($student);

        return view('student.transactions.index', compact('historyViewModel', 'statsViewModel'));
    }
}
