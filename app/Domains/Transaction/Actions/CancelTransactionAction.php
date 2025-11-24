<?php

namespace App\Domains\Transaction\Actions;

use App\Domains\Transaction\Models\Transaction;

class CancelTransactionAction
{
    public static function execute(Transaction $transaction): void
    {
        if (! $transaction->isProcessing()) {
            return;
        }

        FailTransactionAction::execute($transaction, 'Cancelled by user');
    }
}
