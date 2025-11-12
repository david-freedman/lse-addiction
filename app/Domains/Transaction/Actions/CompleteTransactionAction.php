<?php

namespace App\Domains\Transaction\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;

class CompleteTransactionAction
{
    public static function execute(Transaction $transaction): void
    {
        if ($transaction->isCompleted()) {
            return;
        }

        $transaction->update([
            'status' => TransactionStatus::Completed,
            'completed_at' => now(),
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Transaction,
            'subject_id' => $transaction->id,
            'activity_type' => ActivityType::TransactionCompleted,
            'description' => 'Transaction completed',
            'properties' => [
                'transaction_number' => $transaction->transaction_number,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'customer_id' => $transaction->customer_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
