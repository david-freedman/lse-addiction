<?php

namespace App\Applications\Http\Admin\Transaction\Controllers;

use App\Domains\Transaction\Actions\ExportTransactionsAction;
use App\Domains\Transaction\Data\TransactionFilterData;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportTransactionsController
{
    public function __invoke(Request $request, ExportTransactionsAction $action): StreamedResponse
    {
        $filters = TransactionFilterData::validateAndCreate($request->all());
        $format = $request->get('format', 'csv');

        return $format === 'excel'
            ? $action->toExcel($filters)
            : $action->toCsv($filters);
    }
}
