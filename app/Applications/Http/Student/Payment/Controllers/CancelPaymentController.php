<?php

namespace App\Applications\Http\Student\Payment\Controllers;

use App\Domains\Transaction\Actions\CancelTransactionAction;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CancelPaymentController
{
    public function __invoke(Request $request, Transaction $transaction): RedirectResponse
    {
        $student = $request->user();

        if ($transaction->student_id !== $student->id) {
            abort(403, 'Unauthorized access to transaction');
        }

        CancelTransactionAction::execute($transaction);

        return redirect()->route('student.transactions.index')
            ->with('success', 'Оплату скасовано');
    }
}
